<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\ClinicPoint;
use App\Models\ComplaintBox;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactUsController extends Controller
{
    //  get message all customers
    function index()
    {
        if (Auth::user()->app_type == 3 || Auth::user()->app_type == 7) {
            $auth_app = Auth::user()->id;
            $blade = 'doctors.questions.index';
        } else {
            $auth_app = !empty(Auth::user()->parent_id) ? Auth::user()->parent_id : Auth::user()->id;
            $blade = 'ContactUs.contactUs';
        }
        $messages = ComplaintBox::where('clinic_id', $auth_app)->latest()->paginate();
        return view($blade, compact('messages'));
    }

    function reply($id)
    {
        $reply = ComplaintBox::where('id', $id)->first();
        return view('doctors.questions.reply', compact('reply'));
    }

    public function add_reply($id, Request $request)
    {
        $add_reply = ComplaintBox::whereId($id)->first();
        if (empty($add_reply->reply)) {
            $data = $request->all();
            $data['clinic_id'] = $add_reply->clinic_id ?? null;
            $data['content_ar'] = $request->message;
            $data['content_en'] = $request->message;
            $data['point'] = 5;
            $create_point = ClinicPoint::create($data);
        }
        $add_reply->reply = $request->message;
        $add_reply->save();

        Notifications::create([
            'clinic_id' => $add_reply->clinic_id,
            'user_id' => $add_reply->user_id,
            'receiver_id' => $add_reply->user_id,
            'type' => 1,
            'app_type' => 1,
            'title_ar' => 'Reply received',
            'title_en' => 'Reply received',
            'message_ar' => 'Your message has a new reply.',
            'message_en' => 'Your message has a new reply.',
        ]);

//        ContactUs::send_email($add_reply);
        session()->flash('success', trans('admin.send_reply'));
        return redirect()->back();
    }

    // delete message
    function delete_message($id)
    {
        ComplaintBox::where('id', $id)->delete();
        session()->flash('success', trans('admin.deleted'));
        if (auth()->user()->app_type == 6) {
            return response()->json(['status' => true, 'message' => trans('messages.deleted')]);
        } else {
            return redirect()->back();
        }

    }
}
