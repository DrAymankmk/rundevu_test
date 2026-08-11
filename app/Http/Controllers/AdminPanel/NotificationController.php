<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    function index(Request $request)
    {
        $query = Notifications::where('clinic_id', Auth::user()->id)->orderBy('id', 'desc')->select('id', 'title_' . $this->lang() . ' as title', 'message_' . $this->lang() . ' as message', 'flag', 'url', 'coupon_status', 'image','created_at');
        if ($request->date_from) {
            $query->whereBetween('created_at', array($request->date_from, $request->date_to));
        }
        $notifications = $query->paginate(20);
        if (Auth::user()->app_type == 3 || Auth::user()->app_type == 5 || Auth::user()->app_type == 6  || Auth::user()->app_type == 25 || Auth::user()->app_type == 26 || Auth::user()->app_type == 10) {
            return view('doctors.notifications.index', compact('notifications'));
        }
        return view('setting.index', compact('notifications'));

    }
}
