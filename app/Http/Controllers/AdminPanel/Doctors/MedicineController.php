<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Day;
use App\Models\DoctorAppointments;
use App\Models\DoctorCondition;
use App\Models\Drugs;
use App\Models\PatientMedicalReport;
use App\Models\PatientService;
use App\Models\ReservationDrug;
use App\Models\Reservations;
use App\Models\ReservationVitalSigns;
use App\Models\Service;
use App\Models\ServicesCategory;
use App\Models\Shift;
use App\Models\ShiftEmployee;
use App\Models\VitalSigns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MedicineController extends Controller
{
    // add medicine
    function add_medicine($reservation_id)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::whereId($reservation_id)->first();
        $data['drugs'] = Drugs::where(['clinic_id' => Auth::user()->parent_id])->where('parent_id', '!=', null)->select('id', 'name_ar', 'name_en', 'status', 'name_' . $this->lang() . ' as title', 'medicine_type', 'concentration_ratio', 'concentration_type')->get();
        $category_analysis_ids = ServicesCategory::where(['type' => 1, 'status' => 1])->pluck('id');
        $data['analysis'] = Service::whereIn('category_id', $category_analysis_ids)->where(['status' => 1, 'type' => 1])->orderBy('id', 'desc')->select('id', 'name_' . $this->lang() . ' as name')->get();
        $data['patient_analysis'] = PatientService::with('services')->where(['user_id' => $reservation->user_id, 'type' => 1])->orderBy('id', 'desc')->get();

        $category_rays_ids = ServicesCategory::where(['type' => 2, 'status' => 1])->pluck('id');
        $data['rays'] = Service::whereIn('category_id', $category_rays_ids)->where(['status' => 1, 'type' => 2])->orderBy('id', 'desc')->select('id', 'name_' . $this->lang() . ' as name')->get();
        $data['patient_rays'] = PatientService::with('services')->where(['user_id' => $reservation->user_id, 'type' => 2])->orderBy('id', 'desc')->get();

        $category_ids = ServicesCategory::where(['type' => 3, 'status' => 1])->pluck('id');
        $data['services'] = Service::whereIn('category_id', $category_ids)->where(['status' => 1, 'type' => 3])->orderBy('id', 'desc')->select('id', 'name_' . $this->lang() . ' as name', 'price')->get();
        $data['patient_services'] = PatientService::with('services')->where(['user_id' => $reservation->user_id, 'type' => 3])->orderBy('id', 'desc')->get();

        return view('doctors.waiting_list.add_medicine', compact('reservation', 'lang', 'data'));
    }

    // send medicine
    function create_medicine($reservation_id, Request $request)
    {
        $reservation = Reservations::where('id', $reservation_id)->first();
        $data = $request->all();
        $i = 0;
        foreach ($request->drug_id as $drug) {
            $data['user_id'] = $reservation->user_id;
            $data['reservation_id'] = $reservation->id;
            $data['doctor_id'] = $reservation->doctor_id;
            $data['drug_id'] = $drug;
            $data['repetition'] = $request->repetition[$i];
            $data['nums_days'] = $request->nums_days[$i];
            $data['notes'] = $request->notes[$i] ?? null;
            $add_medicine = ReservationDrug::create($data);
            $i++;
        }
        if ($add_medicine) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    // create_vital_signs
    function create_vital_signs($reservation_id, Request $request)
    {
        $reservation = Reservations::where('id', $reservation_id)->first();
        $reservation->diagnosis = $request->diagnosis;
        $reservation->symptoms = $request->symptoms;
        $reservation->clinical_examination = $request->clinical_examination;
        $reservation->recommendations = $request->recommendations;
        $reservation->notes = $request->notes;
        $reservation->save();
        $data = $request->all();
        $data['reservation_id'] = $reservation_id;
        $data['pregnant'] = $request->pregnant ?? 0;
        VitalSigns::updateOrCreate(
            [
                'reservation_id' => $reservation_id,
            ],
            $data
        );
        session()->flash('success', trans('messages.Added'));
        return redirect()->back();
    }


    public function getAvailableTimes(Request $request)
    {
        $date = $request->date ?: now()->format('Y-m-d');
        $dayOfWeek = Carbon::parse($date)->format('l');
        $dayId = Day::where('name_en', $dayOfWeek)->value('id');

        $selectedDate = $request->input('date');
        if ($request->doctor_id) {
            $doctor_id = $request->doctor_id;
        } else {
            $doctor_id = auth::user()->id;
        }
        $shift_doctor = ShiftEmployee::where('employee_id', $doctor_id)->where('day_id',$dayId)->where('status',1)->first();
        if ($shift_doctor) {
            $doctor_appointments = Shift::where('id', $shift_doctor->shift_id)->select('id', 'time_from', 'time_to')->first();
        } else {
            $doctor_appointments = null;
        }

        $get_numbers_patient = DoctorCondition::where('doctor_id', $doctor_id)->first();
        if ($get_numbers_patient) {
            $period = $get_numbers_patient->number_patients;
        } else {
            $period = 30;
        }
        if ($doctor_appointments) {
            $doctor_appointments = DoctorAppointments::SplitTime($request, $doctor_appointments->time_from, $doctor_appointments->time_to, $period);
            return response()->json($doctor_appointments);
        } else {
            $doctor_appointments = 0;
            return response()->json($doctor_appointments);
        }

    }

    function new_reservation($reservation_id)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::whereId($reservation_id)->first();
        $new_reservation = Reservations::where(['user_id'=>$reservation->user_id,'doctor_id'=>$reservation->doctor_id,'type'=>2])->first();

        return view('doctors.waiting_list.new_reservation', compact('reservation', 'lang','new_reservation'));
    }

    function destroyReservation($reservation_id)
    {
        $reservation = Reservations::whereId($reservation_id)->delete();
        session()->flash('success', trans('admin.deleted'));
        return redirect()->back();
    }

    function create_schedule_consultation($reservation_id, Request $request)
    {
        $reservation = Reservations::where('id', $reservation_id)->first();
        if ($request->type == 2) {
            $data = $request->all();
            $doctor = auth()->user();
            $clinic = Clinic::where('id', $doctor->id)->select('id', 'parent_id')->first();
            $reception = Clinic::where('parent_id', $clinic->parent_id)->where('app_type', 2)->select('id', 'parent_id')->first();
            $data['booking_number'] = rand(111111111, 999999999);
            $data['clinic_id'] = $clinic->parent_id;
            $data['reception_id'] = $reception->id;
            $data['doctor_id'] = $doctor->id;
            $data['parent_id'] = $reservation->parent_id;
            $data['user_id'] =  $reservation->user_id;
            $data['date'] =  $request->date;
            $data['appointment'] = $request->appointment;
            $create_reservation = Reservations::create($data);
            if ($create_reservation) {
                Reservations::generate_qrCode($create_reservation->booking_number);
            }
            session()->flash('success', trans('admin.new_reservation_success'));
            return redirect()->back();
        }

        $reservation->schedule_consultation_date = $request->schedule_consultation_date;
        $reservation->schedule_consultation_time = $request->schedule_consultation_time;
        $reservation->save();
        session()->flash('success', trans('admin.schedule_consultation_success'));
        return redirect()->back();
    }

    function reservation_notes($reservation_id, Request $request)
    {
        $reservation = Reservations::where('id', $reservation_id)->first();
        $reservation->diagnosis = $request->diagnosis;
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
        if ($reservation) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }
}
