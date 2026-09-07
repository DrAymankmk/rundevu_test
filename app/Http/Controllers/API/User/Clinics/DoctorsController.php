<?php

namespace App\Http\Controllers\API\User\Clinics;
use App\Http\Controllers\API\APIController;
use App\Http\Requests\UserApp\Clinics\ClinicRequest;
use App\Http\Requests\UserApp\DoctorAppointment;
use App\Http\Resources\UserApp\AppointmentResource;
use App\Http\Resources\UserApp\DoctorDetailsResource;
use App\Http\Resources\UserApp\DoctorAppointmentsResource;
use App\Models\Clinic;
use App\Models\ComplaintBox;
use App\Models\Day;
use App\Models\Shift;
use App\Models\ShiftEmployee;
use App\Repositories\App\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
class DoctorsController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    function doctor_details(DoctorAppointment $request)
    {
        $doctor = Clinic::with([
            'owner',
            'degree',
            'specialties.specialties',
            'sub_specialties.specialties',
        ])
            ->where('id', $request->id)
            ->where('app_type', 3)
            ->where('status', 1)
            ->first();

        if (!$doctor) {
            return $this->respondNotFound(trans('messages.something_went_wrong'));
        }

        return $this->success(trans('messages.data'), new DoctorDetailsResource($doctor));
    }

    // get doctors
    function doctor_appointments(DoctorAppointment $request)
    {
        $month = !empty($request->month) ? $request->month : date('m');
//        $doctor_appointments = ShiftEmployee::whereMonth('dateA', $month)->whereYear('dateA', date('Y'))->where('employee_id', $request->id)->select('id', 'dateA', 'status')->paginate(31);
        $doctor_appointments = ShiftEmployee::where('employee_id', $request->id)->select('id', 'dateA','day_id', 'status')->paginate(31);

        $today = Carbon::today();

        $doctor_appointments = ShiftEmployee::where('employee_id', $request->id)
            ->where('status', 1)
            ->select('id', 'day_id', 'status')
            ->get()
            ->map(function ($shift) use ($today) {

                // نحسب أقرب يوم قادم
                $targetDay = $shift->day_id;

                $currentDay = $today->dayOfWeekIso; // 1=Monday ... 7=Sunday

                $diff = $targetDay - $currentDay;

                if ($diff < 0) {
                    $diff += 7;
                }

                $nearestDate = $today->copy()->addDays($diff);

                return [
                    'id' => $shift->id,
                    'day_id' => $shift->day_id,
                    'dateA' => $nearestDate->toDateString(),
                    'status' => $shift->status,
                ];
            })
            ->sortBy('dateA')
            ->values();
            
        return $this->success('Doctor Appointments', $doctor_appointments);

        $appointments_list = AppointmentResource::collection($doctor_appointments)->response()->getData();
        return $this->success('Doctor Appointments', $appointments_list);
    }

    // get doctors
    function get_date_appointments(DoctorAppointment $request)
    {
        $date = !empty($request->date) ? $request->date : date('Y-m-d');
        $now = date('H:i:s');
        $dayOfWeek = date("l", strtotime($date));
        $day_id = Day::where('name_en', 'like', "%$dayOfWeek%")->pluck('id')->first();
        $shift_doctor = ShiftEmployee::where('employee_id', $request->id)->where('day_id',$day_id)->where('status',1)->latest()->first();
        if ($shift_doctor) {
//            $doctor_appointments = Shift::where('id', $shift_doctor->shift_id)->select('id', 'time_from', 'time_to')->first();
            $doctor_appointments = Shift::where('id', $shift_doctor->shift_id)
//                ->where('time_to', '>=', $now) // Only fetch shifts that still have time left
                ->select('id', 'time_from', 'time_to')
                ->first();
        } else {
            $doctor_appointments = null;
        }

//        $doctor_appointments = DoctorAppointments::where('day_id', $day_id)->where('doctor_id', $request->id)->select('id', 'date_from', 'date_to', 'period')->first();
            $appointments_list = new DoctorAppointmentsResource($doctor_appointments);
        return response()->json([
            'status'=>200,
            'message'=>'get status Doctor Appointments',
            'online_payment'=>true,
            'data'=> !empty($doctor_appointments) ? $appointments_list : collect(array('data'=>[])),
        ]);
//            return $this->success('get status Doctor Appointments', !empty($doctor_appointments) ? $appointments_list : collect(array('data'=>[])));

    }


    // send complaint
    public function doctor_ask(ClinicRequest $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $data = $request->all();
        $data['clinic_id'] = $request->id;
        $data['complain'] = $request->question;
        $data['user_id'] = $check_authorization->id;
        ComplaintBox::create($data);
        return $this->respondWithMessage(trans('user.doctors.send_question'));
    }



}
