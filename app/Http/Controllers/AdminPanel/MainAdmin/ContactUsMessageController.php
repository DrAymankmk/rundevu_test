<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactUsMessageController extends Controller
{
    public function index()
    {
        $messages = ContactUs::query()
            ->with('readByClinic:id,name')
            ->latest()
            ->get();

        return view('main_admin.contactUs.index', compact('messages'));
    }

    public function markAsRead(ContactUs $contactUs)
    {
        if (! $contactUs->is_read) {
            $contactUs->markAsRead(Auth::id());
        }

        session()->flash('success', __('main.contact_us_marked_read'));

        return redirect()->route('contact-us.index');
    }
}
