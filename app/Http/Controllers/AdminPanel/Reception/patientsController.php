<?php

namespace App\Http\Controllers\AdminPanel\Reception;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Country;
use App\Models\InsuranceClasses;
use App\Models\InsuranceCompanies;
use App\Models\PatientService;
use App\Models\Regions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class patientsController extends Controller
{
    //  create patient
    function add_patient()
    {
        $insurance_companies = InsuranceCompanies::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
        $cities = City::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
        $nationality = Country::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
        return view('reception.add-patient', compact('insurance_companies', 'cities', 'nationality'));
    }

    // get classes insurance from companies
    public function getInsuranceClasses(Request $request)
    {
        $insurance_classes = InsuranceClasses::where(['company_id' => $request->company_id, 'status' => 1])->select('id', 'name_' . $this->lang() . ' as name')->orderBy('id', 'desc')->get();
        $regions = Regions::where(['city_id' => $request->city_id, 'status' => 1])->select('id', 'name_' . $this->lang() . ' as name')->orderBy('id', 'desc')->get();
        return response()->json($insurance_classes);
    }

    public function getRegions(Request $request)
    {
        $regions = Regions::where(['city_id' => $request->city_id, 'status' => 1])->select('id', 'name_' . $this->lang() . ' as name')->orderBy('id', 'desc')->get();
        return response()->json($regions);
    }

    // create patient
    public function create_patient(Request $request)
    {
        $patient_id = $request->patient_id;
        $query = User::where(function($query) use ($request) {
            $query->where('phone', $request->phone);

            if ($request->filled('email')) {
                $query->orWhere('email', $request->email);
            }
        });
        if ($patient_id) {
            $query->where('id', '!=', $patient_id);
        }
        $check_email = $query->first();
        if ($check_email) {
            $message = trans('admin.account_exists');
            return response()->json([
                'status' => 0,
                'type' => 'error',
                'title' => trans('admin.error'),
                'message' => $message,
            ]);
        }
        $patient = $request->all();
        if (!$patient_id) {
            $patient['platform'] = 3;
            $patient['ID_Number'] = Str::random(10);
            $patient['jwt_token'] = Str::random(75);
            $patient['file_number'] = $request->gender . '' . $request->insurance_card_number;
        }
        $patient['name'] = $request->name . "-" . $request->father_name . "-" . $request->Grandfather_name . "-" . $request->family_name;
        $patient['reception_id'] = auth()->user()->id;
        $add_patient = User::updateOrCreate(
            [
                'id' => $request->patient_id ?? null,
            ],
            $patient
        );
//        $add_patient = User::create($patient);
        if ($add_patient) {
            if (!$patient_id) {
                $message = trans('admin.Added');
            } else {
                $message = trans('admin.updated');
            }
            return response()->json([
                'status' => 1,
                'type' => 'success',
                'title' => trans('admin.Successfully'),
                'message' => $message,
                'route' => route('patients')
            ]);
        }
        $message = trans('admin.something_went_wrong');
        return response()->json([
            'status' => 0,
            'type' => 'error',
            'title' => trans('admin.error'),
            'message' => $message,
        ]);
    }


    // get patients
    public function patients(Request $request)
    {
        $search = $request->get('search');

        $reception_ids = Clinic::where('parent_id', auth()->user()->parent_id)
            ->where('app_type', 2)
            ->pluck('id');

        $query = User::with(['nationality', 'city'])
            ->whereIn('reception_id', $reception_ids);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('ID_Number', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('nationality', function ($q2) use ($search) {
                        $q2->where('name_ar', 'like', '%' . $search . '%')
                            ->orWhere('name_en', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('city', function ($q2) use ($search) {
                        $q2->where('name_ar', 'like', '%' . $search . '%')
                            ->orWhere('name_en', 'like', '%' . $search . '%');
                    });
            });
        }

        $patients = $query->latest()->get();

        foreach ($patients as $patient) {
            $patient->patient_card = $this->patient_Card($patient);
        }

        return view('reception.patients', compact('patients', 'search'));
    }

    function patient_Card($patient)
    {
        if ($patient->expired_date <= date('Y-m-d')) {
            $status = 'invalid';
        } else {
            $status = 'valid';
        }
        return $status;
    }

    // edit patient
    function edit_patient($patient_id)
    {
        $patient = User::whereId($patient_id)->first();
        $nameWithoutHyphens = str_replace('-', ' ', $patient->name); // Replace hyphens with spaces
        $nameParts = explode(' ', $nameWithoutHyphens); // Split the name into parts
        $insurance_companies = InsuranceCompanies::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
        $cities = City::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
        $nationality = Country::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
        return view('reception.edit-patient', compact('insurance_companies', 'cities', 'nationality', 'patient', 'nameParts'));
    }


    function services($patient_id)
    {
        $patient_services = PatientService::where(['user_id'=>$patient_id,'clinic_id'=>auth()->user()->parent_id])->where('status', 1)->get();
        return view('reception.services', compact('patient_services','patient_id'));
    }


    function confirm_service($id)
    {
        $confirm_service = PatientService::where('id', $id)->first();
//        $confirm_service->reception_id = auth()->user()->id;
        $confirm_service->confirm = 1;
        $confirm_service->save();
        $message = trans('admin.confirm_service');
        return response()->json($message);
    }

    function transfer_service($id)
    {
        $transfer_service = PatientService::where('id', $id)->first();
//        $confirm_service->reception_id = auth()->user()->id;
        $transfer_service->status = 1;
        $transfer_service->save();
        $message = trans('admin.transfer_service');
        return response()->json($message);
    }


    public function search(Request $request)
    {
        $search = $request->get('search');

        $patients = User::with(['nationality', 'city'])
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('ID_Number', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('nationality', function ($q2) use ($search) {
                            $q2->where('name_ar', 'like', '%' . $search . '%')
                                ->orWhere('name_en', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('city', function ($q2) use ($search) {
                            $q2->where('name_ar', 'like', '%' . $search . '%')
                                ->orWhere('name_en', 'like', '%' . $search . '%');
                        });
                });
            })
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'ID_Number' => $patient->ID_Number,
                    'name' => $patient->name,
                    'age' => \Carbon\Carbon::parse($patient->dob)->diff(\Carbon\Carbon::now())->y,
                    'phone' => $patient->phone,
                    'image' => $patient->image,
                    'nationality_name' => app()->getLocale() == 'en' ?
                        $patient->nationality->name_en : $patient->nationality->name_ar,
                    'city_name' => app()->getLocale() == 'en' ?
                        $patient->city->name_en : $patient->city->name_ar,
                    'gender_display' => $patient->gender == 1 ?
                        trans('admin.male') : trans('admin.female'),
                ];
            });

        return response()->json([
            'patients' => $patients
        ]);
    }
}
