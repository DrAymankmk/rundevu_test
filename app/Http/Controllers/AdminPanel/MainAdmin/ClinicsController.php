<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppType;
use App\Models\City;
use App\Models\Clinic;
use App\Models\CmsLanguage;
use App\Models\ClinicContract;
use App\Models\ClinicRating;
use App\Models\ClinicSpecialist;
use App\Models\InsuranceClasses;
use App\Models\Package;
use App\Models\Specialty;
use App\Models\SubscriptionsPackageClinic;
use App\Services\Seo\SeoSyncService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ClinicsController extends Controller
{
    // get all clinics
    public function index(Request $request)
    {

        $query = Clinic::where('app_type', 1)
            ->with('contract')
            ->withCount(['clinic_points', 'doctors'])
            ->latest();

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        } elseif ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->status !== null) {
            $query->where('status', $request->status);
        }

        $clinics = $query->paginate(20);

        return view('main_admin.clinics.index', compact('clinics'));
    }

    // get clinic details
    function clinic_details($clinic_id)
    {
        $clinic = Clinic::with([
            'doctors' => function ($query) {
                $query->withCount('reservations');
            },
            'specialties',
            'currentPackage',
            'contract',
            'seoMeta.translations',
        ])
            ->withCount(['clinic_points', 'posts'])
            ->whereId($clinic_id)
            ->first();
        $app_types = AppType::whereIn('id', [2,3,5,8,9,10,25,26,27])->get();
        $data['rating'] = ClinicRating::where('clinic_id', $clinic_id)->where('comment', '!=', null)->avg('rate_value');
        $languages = CmsLanguage::active()->ordered()->get();
        return view('main_admin.clinics.details', compact('clinic','data','app_types', 'languages'));
    }

    // doctor details
    function doctor_details($doctor_id)
    {
        $doctor = Clinic::with(['specialties', 'seoMeta.translations'])->withCount('complaints','reservations_done','reservations_cancel','condition')->whereId($doctor_id)->first();
        $groupedReservations = $doctor->reservations->groupBy('status_id'); // Or name_en based on locale
        $languages = CmsLanguage::active()->ordered()->get();

        return view('main_admin.clinics.doctor_details', compact('doctor','groupedReservations', 'languages'));
    }

    public function update_clinic($id, Request $request)
    {
        $edit_clinic = clinic::where('id', $id)->first();
        $validator = $this->contractValidator($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(array_merge(['_token'], $this->contractFields()));
        $data['created_by'] = Auth::user()->id;
        $edit_clinic->update($data);
        $this->syncContract($edit_clinic, $request);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }

    public function update_clinic_seo($id, Request $request, SeoSyncService $seoSync)
    {
        $clinic = Clinic::findOrFail($id);

        $request->validate(SeoSyncService::validationRules());

        $seoSync->sync($clinic, $request);

        session()->flash('success', trans('messages.updated'));

        return redirect()->back();
    }




    public function update_status_clinic($id, $status)
    {
        $status_clinic = Clinic::where('id', $id)->first();
        $status_clinic->status = $status;
        $status_clinic->save();
        return response()->json([
            'status' => 1,
            'type' => 'success',
            'title' => trans('admin.Successfully'),
            'message' => trans('admin.update_status'),
            'route' => route('clinics')
        ]);
    }

    // delete clinic
    function destroy_clinic(Request $request, $id)
    {
        $clinic = Clinic::find($id);
        if (!$clinic) {
            return $this->deleteResponse($request, false, __('Clinic not found'), 404);
            return response()->json(['status' => false, 'message' => 'العيادة غير موجود'], 404);
        }
        // تنفيذ الحذف
        if ($clinic->doctors()->exists()) {
            return $this->deleteResponse(
                $request,
                false,
                __('Cannot delete this clinic because it has doctors.'),
                422
            );
        }

        try {
            $clinic->delete();
        } catch (QueryException $e) {
            return $this->deleteResponse(
                $request,
                false,
                __('Cannot delete this clinic because it is linked to other data.'),
                422
            );
        }

        return $this->deleteResponse($request, true, trans('messages.deleted'));
    }

    private function deleteResponse(Request $request, bool $status, string $message, int $code = 200)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $code);
        }

        session()->flash($status ? 'success' : 'error', $message);
        return redirect()->back();
    }

    public function loadTabContent($id, $clinic_id)
    {
        $type = AppType::findOrFail($id);
        $accounts = Clinic::where('app_type', $id)->where('parent_id',$clinic_id)->latest()->get();
        return view('main_admin.partials.accounts_clinics_table', compact('type', 'accounts'))->render();
    }

    function app_types($id)
    {
        $type = AppType::whereId($id)->select('id','name_'.app()->getLocale().' as name')->first();
        $accounts = Clinic::where('app_type', $id)->latest()->get();
        return view('main_admin.app_types', compact('type', 'accounts'));
    }



    public function getAll()
    {
        $clinics = Clinic::where('app_type',1)->select('id', 'name')->get();
        return response()->json($clinics);
    }






    function add_clinic()
    {
        $specialties = Specialty::where('parent_id', null)->where('status', 1)->orderBy('id', 'desc')->get();
        $cities = City::where('status', 1)->get();
        $packages = Package::where('status', 1)->get();
        return view('main_admin.clinics.create', compact('cities', 'specialties','packages'));
    }

    public function create_clinic(Request $request)
    {
        // التحقق من وجود حساب مسبق
        $check_account = Clinic::where('phone', $request->phone)->orWhere('email', $request->email)->first();
        if ($check_account) {
            session()->flash('error', __('main.email_or_phone_exists'));
            return redirect()->back()->withInput();
        }

        // التحقق من وجود الباقة
        $package = Package::find($request->package_id);
        if (!$package) {
            session()->flash('error', __('main.package_not_found'));
            return redirect()->back()->withInput();
        }

        $validator = $this->contractValidator($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(array_merge(['_token', 'specialty_id'], $this->contractFields()));
        // إعداد البيانات
        $data['password'] = Hash::make($request->password);
        $data['jwt_token'] = Str::random(75);
        $data['app_type'] = 1;
        $data['package_end_date'] = Carbon::now()->addDays($package->duration);

        // إنشاء العيادة
        $create_clinic = Clinic::create($data);

        if ($create_clinic) {
            $this->syncContract($create_clinic, $request);

            // إنشاء اشتراك الباقة
            SubscriptionsPackageClinic::create([
                'clinic_id'  => $create_clinic->id,
                'package_id' => $package->id,
                'start_date' => now(),
                'end_date'   => Carbon::now()->addDays($package->duration),
                'status'     => 1,
            ]);

            // إضافة التخصصات
            if ($request->specialty_id && is_array($request->specialty_id)) {
                foreach ($request->specialty_id as $specialty) {
                    if (!empty($specialty)) {
                        ClinicSpecialist::create([
                            'clinic_id' => $create_clinic->id,
                            'specialty_id' => $specialty,
                            'type' => 1,
                        ]);
                    }
                }
            }

            session()->flash('success', __('main.clinic_registered_successfully'));
        } else {
            session()->flash('error', __('main.registration_failed'));
        }

        return redirect()->back();
    }

    private function contractFields(): array
    {
        return [
            'contract_model',
            'commission_rate',
            'annual_subscription_amount',
            'annual_subscription_starts_at',
            'annual_subscription_ends_at',
            'rendezvous_badge_enabled',
        ];
    }

    private function contractValidator(Request $request)
    {
        return Validator::make($request->all(), [
            'contract_model' => ['nullable', Rule::in([
                ClinicContract::ANNUAL_SUBSCRIPTION,
                ClinicContract::ONLINE_PAYMENT_COMMISSION,
                ClinicContract::CASH_COMMISSION,
            ])],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'annual_subscription_amount' => ['nullable', 'numeric', 'min:0'],
            'annual_subscription_starts_at' => ['nullable', 'date_format:Y-m-d'],
            'annual_subscription_ends_at' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:annual_subscription_starts_at'],
            'rendezvous_badge_enabled' => ['nullable', 'boolean'],
        ]);
    }

    private function syncContract(Clinic $clinic, Request $request): ClinicContract
    {
        $contractModel = $request->input('contract_model', ClinicContract::CASH_COMMISSION);

        return ClinicContract::updateOrCreate(
            ['clinic_id' => $clinic->id],
            [
                'contract_model' => $contractModel,
                'commission_rate' => $contractModel === ClinicContract::ANNUAL_SUBSCRIPTION
                    ? 0
                    : (float) $request->input('commission_rate', 0),
                'annual_subscription_amount' => $contractModel === ClinicContract::ANNUAL_SUBSCRIPTION
                    ? $request->input('annual_subscription_amount')
                    : null,
                'annual_subscription_starts_at' => $contractModel === ClinicContract::ANNUAL_SUBSCRIPTION
                    ? $request->input('annual_subscription_starts_at')
                    : null,
                'annual_subscription_ends_at' => $contractModel === ClinicContract::ANNUAL_SUBSCRIPTION
                    ? $request->input('annual_subscription_ends_at')
                    : null,
                'rendezvous_badge_enabled' => $contractModel === ClinicContract::ANNUAL_SUBSCRIPTION
                    && (bool) $request->input('rendezvous_badge_enabled'),
            ]
        );
    }

}
