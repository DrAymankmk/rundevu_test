<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\ReservationDrug;
use App\Models\Reservations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    function search_patient_file(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::where(['status' => 1])->where(function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->q . '%')
                ->orWhere('ID_Number', $request->q)
                ->orWhere('file_number', $request->q);
        })->first();
        if ($user) {
            $reservation = Reservations::where('user_id', $user->id)->where('doctor_id', $id)->orderBy('id', 'desc')->first();
            if ($reservation) {
                return redirect()->route('patient-file', $reservation->id);
            } else {
                session()->flash('error', trans('admin.no_data'));
                return redirect()->back();
            }
        } else {
            session()->flash('error', trans('admin.no_data'));
            return redirect()->back();
        }

    }
}
