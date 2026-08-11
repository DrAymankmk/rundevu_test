<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserApp\DoctorAppointment;
use App\Models\Admin;
use App\Models\AppType;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\DoctorCondition;
use App\Models\DoctorDegree;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DepartmentEmployeesController extends Controller
{
    // get all department employees
    function index(Request $request, $department_id)
    {
        $check_authorization = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        $data['employees'] = Clinic::where('app_type', $department_id)->where('parent_id', $check_authorization)->orderBy('id', 'desc')->select('id', 'name', 'image', 'email', 'phone', 'gender', 'specialization', 'status', 'degree_id', 'ID_Number', 'app_type')->paginate(20);
        $data['department_id'] = $department_id;
        $data['app_type'] = AppType::where('id', $department_id)->select('id', 'name_' . $this->lang() . ' as name')->first();
        $data['employee_job'] = Clinic::title_job($department_id);
        return view('departments.employees', compact('data'));
    }

    // add department employee
    function create_department_employee(Request $request, $department_id)
    {
        $check_authorization = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;

        $data['app_type'] = AppType::where('id', $department_id)->select('id', 'name_' . $this->lang() . ' as name')->first();
        $data['employee_job'] = Clinic::title_job($department_id);
        $clinic_specialty_ids = ClinicSpecialist::where('clinic_id', $check_authorization)->where('type', 1)->pluck('specialty_id')->toArray();
        $data['specializations'] = Specialty::where('status', 1)->whereIn('id', $clinic_specialty_ids)->orderBy('id', 'desc')->get();
        $data['degree'] = DoctorDegree::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();

        return view('departments.add_employee', compact('data'));
    }

    public function getSubSpecialist(Request $request)
    {
        $sub_specialists = Specialty::where('parent_id', $request->specialist_id)->orderBy('id', 'desc')->select('id', 'name_' . $this->lang() . ' as name')->get();
        return response()->json([
            'specialists_count' => count($sub_specialists),
            'data' => $sub_specialists,
        ]);
//            return response()->json($users);
    }

    // add supervisor in main admin
    function add_department_employee(Request $request, $department_id)
    {
        $auth_user = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        $check_email = Clinic::where('phone', $request->phone)->orwhere('email', $request->email)->first();
        if ($check_email) {
            session()->flash('success', trans('admin.account_exists'));
            return redirect()->back();
        }
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['jwt_token'] = Str::random(75);
        $data['app_type'] = $department_id;
        $data['parent_id'] = $auth_user;
        $add_employee = Clinic::create($data);
        if ($add_employee) {

            if ($department_id == 3) {

                if ($request->appointments_online) {
                    $appointment_doctor = DoctorCondition::updateOrCreate(
                        [
                            'doctor_id' => $add_employee->id,
                        ],
                        [
                            'doctor_id' => $add_employee->id,
                            'appointments_online' => $request->appointments_online,
                            'appointments_reception' => $request->appointments_reception,
                            'consultation_duration' => $request->consultation_duration,
                        ]
                    );
                }

                if ($request->specialist_id) {
                    ClinicSpecialist::updateOrCreate(
                        [
                            'clinic_id' => $add_employee->id,
                            'specialty_id' => $request->specialist_id,
                        ],
                        [
                            'clinic_id' => $add_employee->id,
                            'specialty_id' => $request->specialist_id,
                            'type' => 1,
                        ]
                    );
                }

                if ($request->sub_specialist_ids) {
                    foreach ($request->sub_specialist_ids as $specialists) {
                        ClinicSpecialist::updateOrCreate(
                            [
                                'clinic_id' => $add_employee->id,
                                'specialty_id' => $specialists,
                            ],
                            [
                                'clinic_id' => $add_employee->id,
                                'specialty_id' => $specialists,
                                'type' => 2,
                            ]
                        );
                    }
                }
            } else {
                if ($request->specialist_ids) {
                    foreach ($request->specialist_ids as $specialists) {
                        ClinicSpecialist::updateOrCreate(
                            [
                                'clinic_id' => $add_employee->id,
                                'specialty_id' => $specialists,
                            ],
                            [
                                'clinic_id' => $add_employee->id,
                                'specialty_id' => $specialists,
                                'type' => 1,
                            ]
                        );
                    }
                }
            }

            session()->flash('success', trans('messages.Added'));
        } else {
            session()->flash('failed', trans('messages.something_went_wrong'));
        }

        return redirect()->route('department-employees', $department_id);

    }

    // form update supervisor
    function department_employee_update(Request $request, $id)
    {
        $check_authorization = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        $data['employee'] = Clinic::with(['specialties','condition'])->where('id', $id)->first();
        $data['specialty_ids'] = ClinicSpecialist::with('specialties')->where('clinic_id', $id)->where('type', 1)->where('status', 1)->orderBy('id', 'desc')->pluck('specialty_id')->toArray();
        $data['sub_specialty_ids'] = ClinicSpecialist::with('specialties')->where('clinic_id', $id)->where('type', 2)->where('status', 1)->orderBy('id', 'desc')->pluck('specialty_id')->toArray();
        $clinic_specialty_ids = ClinicSpecialist::where('clinic_id', $check_authorization)->where('type', 1)->pluck('specialty_id')->toArray();
        $data['specializations'] = Specialty::where('status', 1)->whereIn('id', $clinic_specialty_ids)->orderBy('id', 'desc')->get();
        $data['sub_specializations'] = Specialty::whereIn('parent_id', $data['specialty_ids'])->where('status', 1)->where('parent_id', '!=', null)->orderBy('id', 'desc')->get();
        $data['degree'] = DoctorDegree::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
        $doctor_appointments = DoctorCondition::where('doctor_id', $id)->first();
        return view('departments.edit_employee', compact('data','doctor_appointments'));
    }

    //Active or dis active account supervisor
    public function UpdateStatusDepartmentEmployees($id, $status)
    {
        $supervisor = Clinic::where('id', $id)->first();
        $supervisor->status = $status;
        $supervisor->save();
        session()->flash('success', trans('messages.update_status'));
    }

    public function update_department_employee($id, Request $request)
    {
        $data = $request->all();
        $add_employee = Clinic::where('id', $request->id)->first();
        if ($request->password) {
            $add_employee->password = '';
            $add_employee->save();
            if ($add_employee) {
                $add_employee->password = Hash::make($request->password);
                $add_employee->save();
            }
        }
        $add_employee->update($data);


        if ($add_employee->app_type == 3) {


            if ($request->appointments_online) {
                $appointment_doctor = DoctorCondition::updateOrCreate(
                    [
                        'doctor_id' => $id,
                    ],
                    [
                        'doctor_id' => $id,
                        'appointments_online' => $request->appointments_online,
                        'appointments_reception' => $request->appointments_reception,
                        'consultation_duration' => $request->consultation_duration,
                    ]
                );
            }

            if ($request->specialist_id) {
                ClinicSpecialist::updateOrCreate(
                    [
                        'clinic_id' => $id,
                        'specialty_id' => $request->specialist_id,
                    ],
                    [
                        'clinic_id' => $id,
                        'specialty_id' => $request->specialist_id,
                        'type' => 1,
                    ]
                );
            }

            if ($request->sub_specialist_ids) {
                foreach ($request->sub_specialist_ids as $specialists) {
                    ClinicSpecialist::updateOrCreate(
                        [
                            'clinic_id' => $id,
                            'specialty_id' => $specialists,
                        ],
                        [
                            'clinic_id' => $id,
                            'specialty_id' => $specialists,
                            'type' => 2,
                        ]
                    );
                }
            }
        } else {
            $selected = $request->specialist_ids ?: [];
            $current = ClinicSpecialist::where('clinic_id', $id)->where('type', 1)->pluck('specialty_id')->toArray();
            $toAdd = array_diff($selected, $current);
            $toRemove = array_diff($current, $selected);
            foreach ($toAdd as $specialty_id) {
                ClinicSpecialist::create([
                    'clinic_id' => $id,
                    'specialty_id' => $specialty_id,
                    'type' => 1,
                ]);
            }
            if (!empty($toRemove)) {
                ClinicSpecialist::where('clinic_id', $id)->where('type', 1)->whereIn('specialty_id', $toRemove)->delete();
            }
        }
        session()->flash('success', trans('messages.updated'));
        if (Auth::user()->app_type == 2 || Auth::user()->app_type == 3 || Auth::user()->app_type == 5) {
            return redirect()->back();
        }
        return redirect()->route('department-employees', $add_employee->app_type);

    }


    function destroyDepartmentEmployee($id)
    {
        Clinic::where('id', $id)->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }
}
