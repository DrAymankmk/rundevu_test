<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Models\PatientMedicalReport;
use App\Models\ReservationDrug;
use App\Models\Reservations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionRecordController extends Controller
{
    // medical reports
    function prescription_record(Request $request)
    {

        $id = Auth::user()->id;
        $reservation_ids = Reservations::where('clinic_id',auth()->user()->parent_id)->pluck('id');
        $query = ReservationDrug::whereIn('reservation_id', $reservation_ids)->where('status',1)->groupBy('reservation_id')->OrderBy('id','desc');
        if (auth()->user()->app_type == 5) {
//            $reservation_ids = Reservations::where('clinic_id',auth()->user()->parent_id)->pluck('id');
//            $query->whereIn('reservation_id', $reservation_ids)->where('status',1);
            $route = 'diagnosis-display';
        } else {
            $query->where('doctor_id', $id);
            $route = 'medical-prescription';
        }

        if ($request->date_from) {
            $dateFrom = $request->date_from;

            // If $request->date_to is not set, use the same date as $dateFrom
            $dateTo = $request->date_to ?? $dateFrom;

            $query->whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo);
        }
        $data['prescription_record'] = $query->paginate();

        return view('doctors.prescription_record.index', compact('data','route'));
    }

    // patient report
    function patient_report($id, $type)
    {
        $lang = app()->getLocale() ?? 'en';
        if ($type == 2) {
            $user = PatientMedicalReport::where('user_id',$id)->where('parent_id',null)->latest()->first();
            $patient_report = PatientMedicalReport::with('patient_report')->whereId($user->id ?? null)->first();
        } else {
            $patient_report = PatientMedicalReport::with('patient_report')->whereId($id)->first();
        }
        return view('doctors.medical_reports.patient_report', compact('patient_report','lang'));
    }
}
