<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Models\PatientMedicalReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalReportsController extends Controller
{
    // medical reports
    function medical_reports(Request $request)
    {
        $id = Auth::user()->id;
        $query = PatientMedicalReport::where('parent_id', null);
        if ($request->q) {
            $users = User::where('ID_Number', $request->q)->orwhere('phone', $request->q)->pluck('id');
            $query->whereIn('user_id', $users);
        }
        $data['medical_reports'] = $query->paginate();

        return view('doctors.medical_reports.index', compact('data'));
    }

    // patient report
    function patient_report($id, $type = null)
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
