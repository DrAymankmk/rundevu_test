<?php

namespace App\Http\Controllers\AdminPanel\Reception;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Country;
use App\Models\ReservationChat;
use App\Models\Reservations;
use App\Models\StatusConversion;
use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    // attachments
    function attachments($patient_id = null)
    {
        $reception_ids = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 2)->pluck('id');
        $doctor_ids = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 3)->pluck('id');
        $query = StatusConversion::whereIn('reception_id', $reception_ids)->OrwhereIn('doctor_id', $doctor_ids)->where('type', 4)->orderBy('id', 'desc');
        if ($patient_id) {
            $query->where('user_id', $patient_id);
        }
        $attachments = $query->get();
        return view('reception.attachments', compact('attachments','patient_id'));
    }

    function destroy_attachment($id)
    {
        $delete_attachment = StatusConversion::where('id', $id)->first();
        $delete_attachment->delete();
        $message = trans('messages.deleted');
        return response()->json($message);
    }

    // add attachment
    function add_attachment(Request $request)
    {
        $auth_app = auth()->user()->id;
        $data = $request->all();
        $data['reception_id'] = $auth_app;
        $data['user_id'] = $request->patient_id ?? null;
        $add_attachment = StatusConversion::create($data);
        if ($add_attachment) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }
}
