<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Notifications::select('id', 'title_' . $this->lang() . ' as title', 'message_' . $this->lang() . ' as message', 'flag', 'url', 'coupon_status', 'image','created_at','admin_id');
        if ($request->date_from) {
            $query->whereBetween('created_at', array($request->date_from, $request->date_to));
        }
        $notifications = $query->latest()->get();
        return view('main_admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lang = $this->lang();
        $packages = Package::where('status',1)->latest()->select('id','name_'.$lang.' as name','status')->get();
        return view('main_admin.notifications.create',compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'message_en' => 'required|string',
            'message_ar' => 'required|string',
            'choices-single-groups' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'clinic_id' => 'nullable|exists:clinics,id',
        ]);
        $data = $request->all();
        $add_notification = Notifications::create($data);
        if ($add_notification) {
            return redirect()->route('notificationsList.index')
                ->with('success', __('main.notification_created_successfully'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notifications::find($id);
        if (!$notification) {
            return response()->json(['status' => false, 'message' => 'الاشعار غير موجود'], 404);
        }
        // تنفيذ الحذف
        $notification->delete();
        return response()->json(['status' => true, 'message' => trans('messages.deleted')]);
    }
}
