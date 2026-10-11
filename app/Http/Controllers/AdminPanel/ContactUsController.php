<?php

namespace App\Http\Controllers\AdminPanel;

use App\Events\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicPoint;
use App\Models\ComplaintBox;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactUsController extends Controller
{
    // get message all customers
    function index()
    {
        $account = Auth::user();

        if (Auth::user()->app_type == 3 || Auth::user()->app_type == 7) {
            $clinicIds = [$account->id];
            $blade = 'doctors.questions.index';
        } else {
            $auth_app = !empty($account->parent_id) ? $account->parent_id : $account->id;
            $clinicIds = Clinic::where('id', $auth_app)
                ->orWhere('parent_id', $auth_app)
                ->pluck('id')
                ->all();
            $blade = 'ContactUs.contactUs';
        }

        $messages = ComplaintBox::whereIn('clinic_id', $clinicIds)->latest()->paginate();
        return view($blade, compact('messages'));
    }

    public function patientMessages()
    {
        $account = Auth::user();
        $clinicId = $account->parent_id ?: $account->id;
        $pageTitle = trans('admin.patient_messages');
        $isPatientMessages = true;

        $messages = ComplaintBox::with('users')
            ->where('clinic_id', $clinicId)
            ->where('type', 3)
            ->latest()
            ->paginate();

        return view('ContactUs.contactUs', compact('messages', 'pageTitle', 'isPatientMessages'));
    }

    function reply($id)
    {
        $reply = ComplaintBox::where('id', $id)->first();
        return view('doctors.questions.reply', compact('reply'));
    }

    public function add_reply($id, Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $complaint = ComplaintBox::whereId($id)->firstOrFail();

        if (empty($complaint->reply)) {
            $data = $request->all();
            $data['clinic_id'] = $complaint->clinic_id ?? null;
            $data['content_ar'] = $request->message;
            $data['content_en'] = $request->message;
            $data['point'] = 5;
//            ClinicPoint::create($data);
        }

        $complaint->reply = $request->message;
        $complaint->save();

        $notificationData = $this->complaintReplyNotificationData($complaint);
        if ($notificationData) {
            $notification = Notifications::create($notificationData);
            broadcast(new UserNotificationEvent($notification))->toOthers();
        }

//        ContactUs::send_email($complaint);
        session()->flash('success', trans('admin.send_reply'));
        return redirect()->back();
    }

    private function complaintReplyNotificationData(ComplaintBox $complaint)
    {
        $isMainAdminReply = (int) auth()->user()->app_type === 6;
        $titleAr = $isMainAdminReply ? 'رد من الإدارة الرئيسية' : 'رد من مدير العيادة';
        $titleEn = $isMainAdminReply ? 'Reply from main admin' : 'Reply from clinic manager';
        $messageAr = $isMainAdminReply
            ? 'تم الرد على الشكوى من الإدارة الرئيسية.'
            : 'تم الرد على الشكوى من مدير العيادة.';
        $messageEn = $isMainAdminReply
            ? 'Your complaint has a new reply from the main admin.'
            : 'Your complaint has a new reply from the clinic manager.';

        if ((int) $complaint->type === 3) {
            $titleAr = 'رد من الاستقبال';
            $titleEn = 'Reply from reception';
            $messageAr = 'تم الرد على رسالتك من الاستقبال.';
            $messageEn = 'Your message has a new reply from reception.';
        }

        if ($complaint->user_id) {
            return [
                'clinic_id' => $complaint->clinic_id,
                'user_id' => $complaint->user_id,
                'receiver_id' => $complaint->user_id,
                'type' => 1,
                'app_type' => 1,
                'title_ar' => $titleAr,
                'title_en' => $titleEn,
                'message_ar' => $messageAr,
                'message_en' => $messageEn,
            ];
        }

        if ($complaint->clinic_id) {
            $clinic = Clinic::select('id', 'app_type', 'parent_id')->find($complaint->clinic_id);

            if (!$clinic) {
                return null;
            }

            return [
                'clinic_id' => $clinic->parent_id ?: $clinic->id,
                'receiver_id' => $clinic->id,
                'type' => 2,
                'app_type' => $clinic->app_type,
                'title_ar' => $titleAr,
                'title_en' => $titleEn,
                'message_ar' => $messageAr,
                'message_en' => $messageEn,
            ];
        }

        return null;
    }

    // delete message
    function delete_message($id)
    {
        ComplaintBox::where('id', $id)->delete();
        session()->flash('success', trans('admin.deleted'));

        if (auth()->user()->app_type == 6) {
            return response()->json(['status' => true, 'message' => trans('messages.deleted')]);
        }

        return redirect()->back();
    }
}
