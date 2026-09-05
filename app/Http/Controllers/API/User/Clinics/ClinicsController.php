<?php

namespace App\Http\Controllers\API\User\Clinics;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\UserApp\Clinics\ClinicRequest;
use App\Http\Resources\OffersResource;
use App\Http\Resources\UserApp\ClinicDetailsResource;
use App\Http\Resources\UserApp\ClinicDoctorsResource;
use App\Http\Resources\UserApp\SpecialistsClinicsResource;
use App\Http\Resources\UserApp\ClinicsResource;
use App\Models\Clinic;
use App\Models\ClinicOffer;
use App\Models\ClinicSpecialist;
use App\Models\ComplaintBox;
use App\Models\InsuranceCompanies;
use App\Models\Specialty;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ClinicsController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    //  get clinics
    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if ($check_authorization) {
            $city_id = !empty($request->city_id) ? $request->city_id : (int)$check_authorization->city_id;
        }

        $data = [
            'title' => $request->title ?? 'New Notification',
            'message' => $request->message ?? 'Hello from Takafoul!',
            'time' => now()->toDateTimeString(),
        ];

//        event(new Notify($data));
//        broadcast(new Notify($data));

        $user_lat = $check_authorization->lat ?? null;
        $user_lng = $check_authorization->lng ?? null;
        $lat = !empty($request->lat) ? $request->lat : $user_lat;
        $lng = !empty($request->lng) ? $request->lng : $user_lng;
        // if (!empty($lat) && !empty($lng)) {
        //     $query = Clinic::filterByLatLng($lat, $lng, 50);
        // } else {
        //     $query = Clinic::where('app_type', 1)->where('status', 1)->select('id', 'info', 'name', 'image', 'lat', 'lng', 'address')->orderBy('id', 'asc');
        // }
        $query = Clinic::whereIn('app_type', [1, 7])->where('status', 1)->select('id', 'parent_id', 'app_type', 'info', 'name', 'image', 'lat', 'lng', 'address', 'facebook_url', 'instagram_url', 'tiktok_url', 'snapchat_url', 'youtube_url')->orderBy('id', 'asc');
        if ($query->paginate()->total() == 0) {
            $query = Clinic::whereIn('app_type', [1, 7])->where('status', 1)->select('id', 'parent_id', 'app_type', 'info', 'name', 'image', 'lat', 'lng', 'address', 'facebook_url', 'instagram_url', 'tiktok_url', 'snapchat_url', 'youtube_url')->orderBy('id', 'asc');
//            if ($check_authorization) {
//                $query->where('city_id', $city_id);
//            }
        }
        if ($request->q) {
            $query = Clinic::where('name', 'like', "%{$request->q}%")->whereIn('app_type', [1, 7])->where('status', 1)->select('id', 'parent_id', 'app_type', 'info', 'name', 'image', 'lat', 'lng', 'address', 'facebook_url', 'instagram_url', 'tiktok_url', 'snapchat_url', 'youtube_url')->orderBy('id', 'asc');
//            $query->where('name', 'like', "%{$request->q}%");
        }
        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->specialist_id) {
            $get_clinic_ids = ClinicSpecialist::where('specialty_id', $request->specialist_id)->pluck('clinic_id');
            $query->whereIn('id', $get_clinic_ids);
        }
        if ($request->policy_number) {
            $get_company_ids = InsuranceCompanies::where('policy_number', $request->policy_number)->pluck('clinic_id');
            $query->whereIn('id', $get_company_ids);
        }
        $clinics = $query->paginate(10);
//        if ($request->q || $request->specialist_id || $request->city_id || $request->subspecialist_id) {
        $clinics_list = ClinicsResource::collection($clinics)->response()->getData();

        $clinics_list->doctors = $this->featuredDoctorsList($request);
        $clinics_list->clinic_offers = $this->offersList($request);

//        if ($request->filter != 1) {
//            $clinics_list = ClinicsResource::collection($clinics)->response()->getData();
//        } else {
//            $clinic_posts = Posts::whereIn('clinic_id',$clinics->pluck('id'))->where('created_at','>=', Carbon::now()->subdays(7))->orderBy('id','desc')->paginate(10);
//            $clinics_list = ClinicPostsResource::collection($clinic_posts)->response()->getData();
//        }

        return $this->success(trans('messages.userApp.clinics'), $clinics_list);
    }

    private function featuredDoctorsList(Request $request)
    {
        $doctorsById = [];

        foreach (['most_booked', 'top_rated', 'latest'] as $type) {
            foreach ($this->doctorsList($request, $type) as $doctor) {
                $id = (int) $doctor->id;

                if (! isset($doctorsById[$id])) {
                    $doctorsById[$id] = [
                        'doctor' => $doctor,
                        'most_booked_doctors' => false,
                        'top_rated_doctors' => false,
                        'latest_doctors' => false,
                    ];
                }

                $doctorsById[$id][$type . '_doctors'] = true;
            }
        }

        if (empty($doctorsById)) {
            foreach ($this->doctorBaseQuery($request, false)->inRandomOrder()->limit(10)->get() as $doctor) {
                $doctorsById[(int) $doctor->id] = [
                    'doctor' => $doctor,
                    'most_booked_doctors' => false,
                    'top_rated_doctors' => false,
                    'latest_doctors' => false,
                ];
            }
        }

        return collect($doctorsById)
            ->map(function ($item) use ($request) {
                return $this->doctorItem($item['doctor'], $request, [
                    'most_booked_doctors' => $item['most_booked_doctors'],
                    'top_rated_doctors' => $item['top_rated_doctors'],
                    'latest_doctors' => $item['latest_doctors'],
                ]);
            })
            ->values()
            ->all();
    }

    private function doctorsList(Request $request, string $type)
    {
        $query = $this->doctorBaseQuery($request);

        if ($type === 'most_booked') {
            $query->withCount('reservations')
                ->orderByDesc('reservations_count')
                ->orderByDesc('id');
        } elseif ($type === 'top_rated') {
            $query->leftJoin('reservation_rates', function ($join) {
                    $join->on('reservation_rates.doctor_id', '=', 'clinics.id')
                        ->whereNotNull('reservation_rates.comment');
                })
                ->addSelect(DB::raw('COUNT(reservation_rates.id) as rate_count'))
                ->addSelect(DB::raw('AVG(reservation_rates.rate_value) as avg_rate'))
                ->groupBy(
                    'clinics.id',
                    'clinics.app_type',
                    'clinics.name',
                    'clinics.image',
                    'clinics.phone',
                    'clinics.parent_id',
                    'clinics.info',
                    'clinics.info_ar',
                    'clinics.consultation_price',
                    'clinics.degree_id',
                    'clinics.created_at'
                )
                ->having('rate_count', '>', 0)
                ->orderByDesc('avg_rate')
                ->orderByDesc('rate_count')
                ->orderByDesc('clinics.id');
        } else {
            $query->orderByDesc('clinics.id');
        }

        $doctors = $query->limit(10)->get();

        if ($doctors->isEmpty()) {
            $doctors = $this->doctorBaseQuery($request)
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        if ($doctors->isEmpty()) {
            $doctors = $this->doctorBaseQuery($request, false)
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        return $doctors;
    }

    private function doctorItem(Clinic $doctor, Request $request, array $flags)
    {
        $lang = $request->header('lang') == 'en' ? 'en' : 'ar';
        $rateCount = (int) DB::table('reservation_rates')
            ->where('doctor_id', $doctor->id)
            ->whereNotNull('comment')
            ->count();
        $rate = $rateCount > 0
            ? (string) round((float) DB::table('reservation_rates')
                ->where('doctor_id', $doctor->id)
                ->whereNotNull('comment')
                ->avg('rate_value'), 1)
            : '0';

        return [
            'id' => $doctor->id,
            'name' => $doctor->name,
            'image' => $doctor->image,
            'consultation_price' => (string) ($doctor->consultation_price ?? '0'),
            'rate' => $rate,
            'rate_count' => $rateCount,
            'reservations_count' => (int) ($doctor->reservations_count ?? DB::table('reservations')->where('doctor_id', $doctor->id)->count()),
            'degree' => $lang == 'en'
                ? (string) (optional($doctor->degree)->name_en ?? '')
                : (string) (optional($doctor->degree)->name_ar ?? ''),
            'specialties' => $this->doctorSpecialtyNames($doctor, $lang),
            'most_booked_doctors' => $flags['most_booked_doctors'],
            'top_rated_doctors' => $flags['top_rated_doctors'],
            'latest_doctors' => $flags['latest_doctors'],
        ];
    }

    private function doctorSpecialtyNames(Clinic $doctor, string $lang)
    {
        $nameColumn = $lang == 'en' ? 'name_en' : 'name_ar';

        return $doctor->specialties
            ->map(function ($clinicSpecialty) use ($nameColumn) {
                return $clinicSpecialty->specialties ? $clinicSpecialty->specialties->{$nameColumn} : null;
            })
            ->filter()
            ->implode(', ');
    }

    private function doctorBaseQuery(Request $request, bool $withFilters = true)
    {
        $query = Clinic::with(['degree', 'specialties.specialties', 'sub_specialties.specialties'])
            ->where('app_type', 3)
            ->where('status', 1)
            ->select('clinics.id', 'clinics.app_type', 'clinics.name', 'clinics.image', 'clinics.phone', 'clinics.parent_id', 'clinics.info', 'clinics.info_ar', 'clinics.consultation_price', 'clinics.degree_id', 'clinics.created_at');

        if (! $withFilters) {
            return $query;
        }

        if ($request->q) {
            $query->where('clinics.name', 'like', "%{$request->q}%");
        }

        if ($request->city_id) {
            $query->whereHas('owner', function ($owner) use ($request) {
                $owner->where('city_id', $request->city_id);
            });
        }

        if ($request->specialist_id) {
            $query->whereHas('specialties', function ($specialty) use ($request) {
                $specialty->where('specialty_id', $request->specialist_id);
            });
        }

        if ($request->policy_number) {
            $clinicIds = InsuranceCompanies::where('policy_number', $request->policy_number)->pluck('clinic_id');
            $query->whereIn('parent_id', $clinicIds);
        }

        return $query;
    }

    private function activeOffersQuery(Request $request, bool $withFilters = true)
    {
        $offers = ClinicOffer::with('specialty')
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', now()->toDateString());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now()->toDateString());
            })
            ->select('id', 'clinic_id', 'specialty_id', 'title_ar', 'title_en', 'discount', 'start_date', 'end_date')
            ->orderByDesc('id');

        if (! $withFilters) {
            return $offers;
        }

        if ($request->specialist_id) {
            $offers->where('specialty_id', $request->specialist_id);
        }

        if ($request->city_id) {
            $clinicIds = Clinic::where('city_id', $request->city_id)
                ->whereIn('app_type', [1, 7])
                ->where('status', 1)
                ->pluck('id');

            $offers->whereIn('clinic_id', $clinicIds);
        }

        if ($request->policy_number) {
            $clinicIds = InsuranceCompanies::where('policy_number', $request->policy_number)->pluck('clinic_id');
            $offers->whereIn('clinic_id', $clinicIds);
        }

        return $offers;
    }

    private function offersList(Request $request)
    {
        $offers = $this->activeOffersQuery($request)->limit(10)->get();

        if ($offers->isEmpty()) {
            $offers = $this->activeOffersQuery($request, false)
                ->reorder()
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        if ($offers->isEmpty()) {
            $offers = ClinicOffer::with('specialty')
                ->select('id', 'clinic_id', 'specialty_id', 'title_ar', 'title_en', 'discount', 'start_date', 'end_date')
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        return OffersResource::collection($offers)->resolve($request);
    }

    // get specialist and sub specialist
    function specialists(Request $request)
    {
        $specialties = Specialty::where('status', 1)->where('parent_id', null)->orderBy('id', 'desc')->paginate(20);
        $specialties_list = SpecialistsClinicsResource::collection($specialties)->response()->getData();
        return $this->success(trans('messages.specialties.all'), $specialties_list);
    }

    // get offers
    function offers(Request $request)
    {
        $account = Clinic::select('id', 'app_type', 'parent_id')->find($request->id);
        if (!$account) {
            return $this->respondWithError(trans('messages.something_went_wrong'));
        }

        $offers = ClinicOffer::with('specialty')
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', now()->toDateString());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now()->toDateString());
            })
            ->select('id', 'clinic_id', 'specialty_id', 'title_ar', 'title_en', 'discount', 'start_date', 'end_date')
            ->orderBy('id', 'desc');

        if ((int) $account->app_type === 3) {
            $doctorSpecialtyIds = ClinicSpecialist::where('clinic_id', $account->id)
                ->where('type', 1)
                ->where('status', 1)
                ->pluck('specialty_id');

            $offers->where('clinic_id', $account->parent_id)
                ->whereIn('specialty_id', $doctorSpecialtyIds);
        } else {
            $offers->where('clinic_id', $account->id);
        }

        $offers = $offers->paginate(10);
        $offers_list = OffersResource::collection($offers)->response()->getData();
        return $this->success(trans('messages.offers.all'), $offers_list);
    }

    // clinics details
    function clinic_details(ClinicRequest $request)
    {
        $clinic = Clinic::with(['reception_staff', 'medical_staff', 'currentPackage', 'contract'])->where('id', $request->id)->first();
        if (!$clinic) {
            return $this->respondNotFound(trans('messages.something_went_wrong'));
        }

        $clinic_details = new ClinicDetailsResource($clinic);
        return $this->success(trans('messages.data'), $clinic_details);
    }

    // get doctors
    function clinic_doctors(ClinicRequest $request)
    {
        $offers = Clinic::with(['degree', 'specialties.specialties', 'sub_specialties.specialties'])
            ->where('parent_id', $request->id)
            ->where('app_type', 3)
            ->select('id', 'app_type', 'name', 'image', 'phone', 'parent_id', 'info', 'info_ar', 'consultation_price', 'degree_id')
            ->orderBy('id', 'desc')
            ->paginate(10);
        $offers_list = ClinicDoctorsResource::collection($offers)->response()->getData();
        return $this->success(trans('messages.offers.all'), $offers_list);
    }

    // send complaint
    public function clinic_complaint(ClinicRequest $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $data = $request->all();
        $data['clinic_id'] =  $request->id ??  null;
        $data['user_id'] = $check_authorization->id;
        ComplaintBox::create($data);
        return $this->respondWithMessage(trans('user.complaints_box.send'));
    }
}
