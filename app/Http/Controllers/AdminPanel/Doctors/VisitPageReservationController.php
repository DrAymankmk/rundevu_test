<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Models\Drugs;
use App\Models\PatientService;
use App\Models\ReservationDrug;
use App\Models\Reservations;
use App\Models\Service;
use App\Models\ServicesCategory;
use Illuminate\Http\Request;

class VisitPageReservationController extends Controller
{
    // visit page reservation
    function visit_page_reservation($reservation_id)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::whereId($reservation_id)->first();
        $data['drugs'] = ReservationDrug::where(['reservation_id' => $reservation_id])->get();
        $data['patient_analysis'] = PatientService::with('services')->where(['reservation_id' => $reservation_id, 'type' => 1])->orderBy('id', 'desc')->get();

        $data['patient_rays'] = PatientService::with('services')->where(['reservation_id' => $reservation_id, 'type' => 2])->orderBy('id', 'desc')->get();

        $data['patient_services'] = PatientService::with('services')->where(['reservation_id' => $reservation_id, 'type' => 3])->orderBy('id', 'desc')->get();
        $data['patient_invoices'] = PatientService::with('services')->where(['reservation_id' => $reservation_id])->orderBy('id', 'desc')->get();

        return view('doctors.visit_page_reservation.index', compact('reservation', 'lang', 'data'));

    }
}
