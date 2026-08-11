<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Models\DoctorCondition;
use App\Models\ReservationChat;
use App\Models\Reservations;
use App\Models\ShiftEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AppointmentsController extends Controller
{
    // get doctor appointments
    function doctor_appointment(Request $request)
    {

        $query = ShiftEmployee::where('employee_id', auth()->user()->id);
        if ($request->date_from) {
            $query->whereBetween('dateA', array($request->date_from, $request->date_to));
        } else {
            $query->whereMonth('dateA', date('m'));
        }
        $data['appointments'] = $query->get();
        $data['condition'] = DoctorCondition::where('doctor_id', auth()->user()->id)->first();
        $data['date_from'] = Session::put('date_from', $request->date_from);
        $data['date_to'] = Session::put('date_to', $request->date_to);
        return view('doctors.appointments.index', compact('data'));
    }

    // add doctor condition
    function add_doctor_condition(Request $request)
    {
        $add_condition = DoctorCondition::updateOrCreate(
            [
                'doctor_id' => auth()->user()->id,
            ],
            [
                'doctor_id' => auth()->user()->id,
                'appointments_online' => $request->appointments_online,
                'appointments_reception' => $request->appointments_reception,
                'number_patients' => $request->number_patients,
                'condition' => $request->condition,
            ]
        );
        if ($add_condition) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }


    // get patient appointments
    function patient_appointment(Request $request)
    {
        $query = Reservations::where(['doctor_id' => auth()->user()->id, 'status_id' => 1]);
        if ($request->date_from) {
            $query->whereDate('date', $request->date_from);
        }
        $data['appointments'] = $query->get();
        $data['date_from'] = Session::put('date_from', $request->date_from);
        return view('doctors.appointments.patient', compact('data'));
    }


    // cancel_reservation
    function cancel_reservation($id)
    {
        $reservation = Reservations::where('id', $id)->first();
        $reservation->status_id = 3;
        session()->flash('success', trans('admin.cancel_reservation'));
        return redirect()->back();
    }

    // send message to user reservation
    function send_contact_reservation($reservation_id, Request $request) {
        $reservation = Reservations::where('id', $reservation_id)->first();
        $data = $request->all();
        $data['user_id'] = $reservation->user_id;
        $data['reception_id'] = $reservation->doctor_id;
        $data['reservation_id'] = $reservation_id;
        $data['message'] = $request->message;
        $data['sender_type'] = 2;
        $send_message = ReservationChat::create($data);
        if ($send_message) {
            session()->flash('success', trans('admin.send_message') . $reservation->user->name ?? null);
        }
        return redirect()->back();
    }
}
