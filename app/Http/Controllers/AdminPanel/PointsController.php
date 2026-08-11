<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\ClinicOffer;
use App\Models\ClinicPoint;
use App\Models\ComplaintBox;
use App\Models\ReservationDrug;
use App\Models\Reservations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointsController extends Controller
{
    // show points

    function index()
    {
        if (Auth::user()->app_type == 3 || Auth::user()->app_type == 5  || Auth::user()->app_type == 6  ||  Auth::user()->app_type == 7 || Auth::user()->app_type == 25 || Auth::user()->app_type == 26 || Auth::user()->app_type == 10) {
            $auth_user = Auth::user()->id;
        } else {
            $auth_user = Auth::user()->parent_id;
        }
        $data['points'] = ClinicPoint::where('clinic_id',$auth_user)->orderBy('id','desc')->select('id','point','content_'. $this->lang().' as content','created_at','clinic_id')->paginate(10);

        if (auth()->user()->app_type == 5) {
            $reservation_ids = Reservations::where('clinic_id',auth()->user()->parent_id)->pluck('id');
            $data['total_points'] = (ReservationDrug::whereIn('reservation_id',$reservation_ids)->count() ) * 5;
        } else {
            $data['total_points'] = (ComplaintBox::where('clinic_id',$auth_user)->count() ) * 5;
        }
        if (Auth::user()->app_type == 3 || auth()->user()->app_type == 5 || Auth::user()->app_type == 6  || Auth::user()->app_type == 25 || Auth::user()->app_type == 26 || Auth::user()->app_type == 10) {
            return view('doctors.points.index', compact('data'));
        } else {
            return view('points.index', compact('data'));

        }
    }
}
