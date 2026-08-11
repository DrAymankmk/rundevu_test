<?php

namespace App\Http\Controllers\AdminPanel\Reception;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\PatientService;
use Illuminate\Http\Request;
use DateTime;
class DoctorsRequestsController extends Controller
{
    // doctors-requests
    function index(Request $request)
    {
        $data['doctors'] = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 3)->get();
        $query = PatientService::whereIn('doctor_id', $data['doctors']->pluck('id') ?? null)->orderBy('id', 'desc');
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from)
                ->whereDate('created_at', '<=', $request->date_to);
        } else {
            $query->whereDate('created_at', date('Y-m-d'));
        }

        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $data['patients_services'] = $query->get();
        return view('reception.doctors-requests', compact('data'));

    }

    function confirm_service($id, $type)
    {
        $action_service = PatientService::where('id', $id)->first();
        if ($type == 1) {
            $action_service->confirm = 1;
            $message = trans('admin.confirm_service');
        } else {
            $message = trans('admin.transfer_service');
        }
        $action_service->save();

        return response()->json($message);
    }

    public function doctor_request_filter(Request $request)
    {
        $fromDate = DateTime::createFromFormat('d/m/Y', $request->date_from);
        $toDate = DateTime::createFromFormat('d/m/Y', $request->date_to) ;

        if (!$toDate) {
            $toDate = $fromDate;
        }
        // Format the dates as 'Y-m-d' (e.g., 2023-12-04)
        $formattedFromDate = $fromDate->format('Y-m-d') ;
        $formattedToDate = $toDate->format('Y-m-d') ;
        $query = PatientService::with('services','doctor','user')->where('clinic_id', auth()->user()->parent_id)->orderBy('id', 'desc');
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $formattedFromDate)
                ->whereDate('created_at', '<=', $formattedToDate);
        } else {
            $query->whereDate('created_at', date('Y-m-d'));
        }

        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }
        $patients_services = $query->get();

        return response()->json(['patients_services' => $patients_services]);
    }
}
