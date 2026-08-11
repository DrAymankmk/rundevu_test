<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\PatientService;
use App\Models\ReservationChat;
use App\Models\ReservationDrug;
use App\Models\Reservations;
use App\Models\SickLeave;
use App\Models\Specialty;
use App\Models\StatusConversion;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientsWaitingController extends Controller
{
    // show drugs
    function index(Request $request)
    {
        $lang = app()->getLocale() ?? 'en';
        $query = Reservations::where('payment_status',1)->whereIn('status_id', [1, 2])->where(['doctor_id' => Auth::user()->id]);
        if ($request->date_from) {
            $carbonDate = Carbon::createFromFormat('Y-m-d', $request->date_from);
//            $carbonDateTo = Carbon::createFromFormat('d/m/Y', $request->date_to);
            $date_from = $carbonDate->format('Y-m-d');
//            $date_date_to = $carbonDateTo->format('Y-m-d');

            $date_to = $date_from;
            $query->whereDate('date', '>=', $request->date_from)
                ->whereDate('date', '<=', $request->date_to);
//            $query->whereBetween('date', [$date_from, $date_from]);
        } else {
            $date_from = date('Y-m-d');
            $query->where('date', date('Y-m-d'));
        }

        $date = new DateTime($date_from);
        $dayName = $date->format('l');
        $data['patients_waiting'] = $query->get();
        $now = now();
        //   $data['users'] = User::whereIn('id',$data['patients_waiting']->groupBy('user_id')->pluck('user_id'))->get();
        foreach ($data['patients_waiting'] as $waiting) {

            // Your time range string
            $timeRange = $waiting->appointment;

// Split the string by the hyphen (-)
            $timeParts = explode('-', $timeRange);

// Get the start time part (index 0)
            $startTime = trim($timeParts[0]); // Remove any leading/trailing spaces

            $endTime = trim($timeParts[1]); // Remove any leading/trailing spaces
// Convert AM/PM format to 24-hour format
            $endTimeNumber = str_replace(['am', 'pm'], '', $endTime);

// Create a Carbon object for the end time
            $endTimeCarbon = Carbon::parse($endTime);

// Add the end time to the current date (assuming it's today)
            $fullEndTime = Carbon::today()->setTime($endTimeCarbon->hour, $endTimeCarbon->minute, $endTimeCarbon->second);

// Now $fullEndTime contains the complete date and time in 24-hour format
            $fullEndTime->format('H:i'); // Output: 2023-01-01 12:00:00


            $startTime = Carbon::createFromTimeString($startTime);
            $endTime = Carbon::createFromTimeString($fullEndTime);


            if ($now->isBetween($startTime, $endTime, true)) {
                // Current time is between '10:00 am' and '12:00 pm'
                $waiting->isCurrentTurn = true;
            } else {
                $waiting->isCurrentTurn = false;
            }

// Convert the start time to a Carbon instance
            $startTimeCarbon = Carbon::parse($startTime);

// Output the start time
            "Start Time: " . $startTimeCarbon->format('h:i A');


            // Set the specific time (11:30 AM)
            $specificTime = Carbon::createFromFormat('H:i A', $startTimeCarbon->format('h:i A'));

// Get the current time
            $currentTime = Carbon::now();

// Calculate the time difference
            $timeDifference = $specificTime->diff($currentTime);

// Extract the hours and minutes from the time difference
            $hours = $timeDifference->h;
            $minutes = $timeDifference->i;

// Output the time difference
            if ($lang == 'ar') {
                $waiting->waiting_time = " {$hours} ساعة و {$minutes} دقيقة ";
            } else {
                $waiting->waiting_time = " {$hours} hours and {$minutes} minutes";
            }
        }
        return view('doctors.waiting_list.index', compact('data', 'date_from', 'dayName'));
    }

    // medical prescription
    function medical_prescription($reservation_id)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::with('reservation_drugs')->whereId($reservation_id)->first();
        return view('doctors.waiting_list.medical_prescription', compact('reservation', 'lang'));
    }

    // previous_revelations

    function previous_revelations($reservation_id)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::with('reservation_drugs')->whereId($reservation_id)->first();
        $reservation_ids = Reservations::where('id','!=',$reservation_id)->whereDate('date', '<',$reservation->date)->where(['user_id'=>$reservation->user_id,'clinic_id'=>auth()->user()->parent_id])->pluck('id');
        $user = User::where('id',$reservation->user_id)->first();
        $data['visit_patient'] = Reservations::select('id', 'user_id', 'status_id', 'doctor_id', 'date', DB::raw('DAYNAME(created_at) as day_name'))
            ->whereIn('status_id', [1, 2, 3])
            ->whereIn('id', $reservation_ids)
            ->get();
        $data['patient_invoices'] = PatientService::with('services')->whereIn('reservation_id', $reservation_ids)->orderBy('id', 'desc')->get();
        $data['patient_services'] = PatientService::where('type', 3)->whereIn('reservation_id', $reservation_ids)->orderBy('id', 'desc')->get();
        $data['patient_rays'] = PatientService::where('type', 2)->whereIn('reservation_id', $reservation_ids)->orderBy('id', 'desc')->get();
        $data['patient_analysis'] = PatientService::where('type', 1)->whereIn('reservation_id', $reservation_ids)->orderBy('id', 'desc')->groupBy('reservation_id')->get();
        $data['leave_companion'] = SickLeave::with('reservation')->whereIn('reservation_id', $reservation_ids)->where(['type'=>2])->orderBy('id','desc')->get();
        $data['sick_leaves'] = SickLeave::with('reservation')->whereIn('reservation_id', $reservation_ids)->where(['type'=>1])->orderBy('id','desc')->get();
        $data['attachment'] = StatusConversion::where('type', 4)->whereIn('reservation_id', $reservation_ids)->orderBy('id', 'desc')->get();
        $data['drugs'] = ReservationDrug::where(['reservation_id' => $reservation_id])->get();
        $data['reservations'] = Reservations::whereIn('id', $reservation_ids)->get();
        $data['reservation_id'] = $reservation_id;
        return view('doctors.waiting_list.previous_revelations', compact('user', 'lang', 'data', 'user'));
    }

    // patient file
    function patient_file($reservation_id)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::with('reservation_drugs')->whereId($reservation_id)->first();
        $user = User::where('id', $reservation->user_id)->first();

        $data['visit_patient'] = Reservations::select('id', 'user_id', 'status_id', 'doctor_id', 'date', DB::raw('DAYNAME(created_at) as day_name'))
            ->whereIn('status_id', [1, 2, 3])
            ->where('user_id', $reservation->user_id)
            ->get();
        $data['patient_services'] = PatientService::where('type', 3)->where('user_id', $reservation->user_id)->orderBy('id', 'desc')->get();
        $data['patient_rays'] = PatientService::where('type', 2)->where('user_id', $reservation->user_id)->orderBy('id', 'desc')->get();
        $data['patient_analysis'] = PatientService::where('type', 1)->where('user_id', $reservation->user_id)->orderBy('id', 'desc')->get();

        return view('doctors.waiting_list.patient_file', compact('reservation', 'lang', 'user', 'data'));
    }

    function patient_services($reservation_id, $type)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::whereId($reservation_id)->first();
        $user = User::where('id', $reservation->user_id)->first();
        $auth_user = Auth::user()->id;
        $data['specialty_ids'] = ClinicSpecialist::with('specialties')->where('clinic_id', $auth_user)->where('type', 1)->where('status', 1)->orderBy('id', 'desc')->pluck('specialty_id')->toArray();
        $data['specializations'] = Specialty::where('status', 1)->where('parent_id', null)->orderBy('id', 'desc')->select('id', 'name_' . $this->lang() . ' as name')->get();
        $data['type'] = $type;
        $data['attachment'] = StatusConversion::where('reservation_id',$reservation_id)->where('type',$type)->orderBy('id', 'desc')->get();
        if ($type == 1) {
            $data['title'] = trans('admin.status_conversion');
        } elseif ($type == 2) {
            $data['title'] = trans('admin.consult_doctor');
        } elseif ($type == 3) {
            $data['title'] = trans('admin.treatment_plan');
        } else {
            $data['title'] = trans('admin.attachment');
        }
        return view('doctors.waiting_list.services.index', compact('user', 'lang', 'data','reservation_id'));
    }


    public function getDoctorsFromSpecialist(Request $request)
    {
        $auth_app = Auth::user()->id;
        $doctor_ids = ClinicSpecialist::where('specialty_id', $request->specialist_id)->where('clinic_id', '!=', $auth_app)->pluck('clinic_id');
        $doctors = Clinic::whereIn('id', $doctor_ids)->where(['status' => 1, 'app_type' => 3])->orderBy('id', 'desc')->select('id', 'name')->get();
        return response()->json([
            'doctors_count' => count($doctors),
            'data' => $doctors,
        ]);
    }

    public function add_status_conversion($reservation_id, Request $request)
    {
        $reservation = Reservations::whereId($reservation_id)->first();
        $auth_app = Auth::user()->id;
        $data = $request->all();
        $data['doctor_id'] = $request->doctor_id ?? $auth_app;
        $data['user_id'] = $reservation->user_id;
        $data['reservation_id'] = $reservation_id;
        $add_status = StatusConversion::create($data);
        if ($add_status) {
            if ($add_status->type == 2) {
                $chat = $request->all();
                $chat['sender_id'] = $auth_app;
                $chat['receiver_id'] = $request->doctor_id;
                $chat['sender_type'] = 2;
                $chat['message'] = $request->notes;
                ReservationChat::create($chat);
            }
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }


    // patient file
    function reservation_finished($reservation_id)
    {
        $reservation = Reservations::whereId($reservation_id)->first();
        $reservation->status_id = 6;
        $reservation->save();
        if ($reservation->user) {
            app(\App\Services\LoyaltyPointsService::class)->award($reservation->user, 'completed_visit', [
                'clinic_id' => $reservation->clinic_id,
                'reservation_id' => $reservation->id,
                'source_type' => Reservations::class,
                'source_id' => $reservation->id,
            ]);
        }
        session()->flash('success', trans('admin.reservation_finished'));
        return redirect()->back();
    }

    // reservation replace
    function replace_reservation($reservation_id, Request $request)
    {

        // Get the first reservation by ID
        $firstReservation = Reservations::find($reservation_id);

// Get the second reservation by another criteria, such as a different ID
        $secondReservation = Reservations::where('id', $request->reservation_id)->first();


        // Swap the user_id values
        $tempUserId = $firstReservation->user_id;
        $firstReservation->user_id = $secondReservation->user_id;
        $secondReservation->user_id = $tempUserId;

        // Save the changes to both reservations
        $firstReservation->save();
        $secondReservation->save();

        // The user_id values have been swapped between the two reservations


//        $get_Reservation = Reservations::whereId($request->reservation_id)->select('id','user_id')->first();
//
//        $reservation = Reservations::whereId($reservation_id)->first();
//        $reservation->user_id = $get_Reservation->user_id;
//
//        $get_Reservation->user_id = $reservation->user_id;
//        $get_Reservation->save();
//        $reservation->save();

        session()->flash('success', trans('admin.reservation_replaced'));
        return redirect()->back();

    }


}
