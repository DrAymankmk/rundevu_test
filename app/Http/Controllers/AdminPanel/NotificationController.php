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
        $account = Auth::user();
        $query = Notifications::where(function ($query) use ($account) {
            $query->where('clinic_id', $account->id)
                ->orWhere(function ($query) use ($account) {
                    $query->where('receiver_id', $account->id)
                        ->where('app_type', $account->app_type);
                })
                ->orWhere('type', 0);
        })->orderBy('id', 'desc')->select('id', 'title_' . $this->lang() . ' as title', 'message_' . $this->lang() . ' as message', 'flag', 'url', 'coupon_status', 'image','created_at');
        if ($request->date_from) {
            $query->whereBetween('created_at', array($request->date_from, $request->date_to));
        }
        $notifications = $query->paginate(20);
        return view('doctors.notifications.index', compact('notifications'));

    }
}
