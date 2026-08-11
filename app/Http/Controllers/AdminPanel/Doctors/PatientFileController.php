<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Models\PatientService;
use App\Models\Reservations;
use App\Models\Service;
use App\Models\ServicesCategory;
use App\Models\SickLeave;
use App\Models\User;
use Illuminate\Http\Request;

class PatientFileController extends Controller
{
    // patient file
    function patient_service_file($reservation_id, $type)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::with('reservation_drugs')->whereId($reservation_id)->first();
        $user = User::where('id', $reservation->user_id)->first();
        $data['type'] = $type;
        if ($type == 1) {
            $data['title'] = trans('admin.analysis');
        } elseif ($type == 2) {
            $data['title'] = trans('admin.rays');
        } else {
            $data['title'] = trans('admin.service');
        }
        $category_ids = ServicesCategory::where(['type' => $type, 'status' => 1])->pluck('id');
        $data['services'] = Service::whereIn('category_id', $category_ids)->where(['status' => 1, 'type' => $type])->orderBy('id', 'desc')->select('id', 'name_' . $this->lang() . ' as name')->get();
        $data['patient_services'] = PatientService::with('services')->where(['reservation_id' => $reservation_id, 'type' => $type])->orderBy('id', 'desc')->get();
        return view('doctors.patient_file.analysis', compact('lang', 'user', 'data','reservation_id'));
    }

    function create_patient_service_file($reservation_id, Request $request)
    {
        $doctor_id = auth()->user()->id;
        $data = $request->all();
        $i = 0;
        $reservation = Reservations::where('id', $reservation_id)->first();
        foreach ($request->service_id as $service) {
            $service_price = Service::whereId($request->service_id)->select('id', 'price')->first();
            $data['user_id'] = $reservation->user_id;
            $data['reservation_id'] = $reservation_id;
            $data['doctor_id'] = $doctor_id;
            $data['service_id'] = $service;
            $data['type'] = $request->type;
//            $data['invoice_id'] = 1;
            $data['clinic_id'] = auth()->user()->parent_id ?? null;
            $data['price'] = $service_price->price;
            $data['notes'] = $request->notes[$i] ?? null;
            $data['discount'] = $request->discount[$i] ?? null;
            $add_services = PatientService::create($data);
            $i++;
        }
        if ($add_services) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }


    function destroyPatientService($id)
    {
        $service = PatientService::where('id', $id)->first();
        $service->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }

    // invoices
    function patient_invoices($user_id, Request $request)
    {
        $lang = app()->getLocale() ?? 'en';
        $user = User::where('id', $user_id)->first();
        $query = PatientService::with('services')->where(['user_id' => $user_id])->orderBy('id', 'desc');
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', array($request->date_from, $request->date_to));
        }
        $data['patient_services'] = $query->get();
        return view('doctors.patient_file.invoices', compact('lang', 'user', 'data'));
    }

    function patient_sick_leave($reservation_id, Request $request)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::whereId($reservation_id)->first();
        $user_id = $reservation->user_id;
        $user = User::where('id', $user_id)->first();
        $data['sick_leave_reservation'] = SickLeave::where(['reservation_id' => $reservation_id])->first();
        $data['sick_leaves'] = SickLeave::with('reservation')->where(['user_id' => $user_id])->orderBy('id','desc')->paginate();
        return view('doctors.patient_file.sick_leave', compact('lang', 'reservation', 'user','data'));
    }

    // create_sick_leave
    function create_sick_leave($reservation_id,Request $request)
    {
        $data = $request->all();
        $data['medical_company'] = $request->medical_company ?? 0;
        $data['impossible_treat'] = $request->impossible_treat ?? 0;
        $data['physician_leave'] = $request->physician_leave ?? 0;
        $data['user_id'] = $request->user_id;
        SickLeave::updateOrCreate(
            [
                'reservation_id' => $reservation_id,
            ],
            $data
        );
        session()->flash('success', trans('messages.Added'));
        return redirect()->back();
    }

    function companion_sick_leave($reservation_id, Request $request)
    {
        $lang = app()->getLocale() ?? 'en';
        $reservation = Reservations::whereId($reservation_id)->first();
        $user_id = $reservation->user_id;
        $user = User::where('id', $user_id)->first();
        $data['sick_leave_reservation'] = SickLeave::where(['reservation_id' => $reservation_id])->first();
        $data['sick_leaves'] = SickLeave::with('reservation')->where(['user_id' => $user_id])->orderBy('id','desc')->paginate();
        return view('doctors.patient_file.companion_sick_leave', compact('lang', 'reservation', 'user','data'));
    }

    // followup patient

    function followup_patient($id)
    {
        $reservation = Reservations::where('id', $id)->first();
        $reservation->follow_up = 1;
        $reservation->save();
        session()->flash('success', trans('admin.followup_patient'));
        return redirect()->back();
    }
}
