<?php

namespace App\Http\Controllers\Frontend;

use App\Events\SystemNotifications\DemoRequested;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Models\ContactUs;
use App\Models\DemoRequest;
class ContactController extends Controller
{
    //
	public function index()
    {
        $cmsPage = CmsPage::query()
            ->where('slug', 'contact')
            ->where('is_active', true)
            ->first();

        $cmsPageSections = collect();
        if ($cmsPage) {
            $cmsPageSections = $cmsPage->sections()
                ->active()
                ->ordered()
                ->with([
                    'translations',
                    'links' => static function ($q) {
                        $q->active()->ordered()->with('translations');
                    },
                    'items' => static function ($q) {
                        $q->active()->ordered()->with([
                            'translations',
                            'links' => static function ($lq) {
                                $lq->active()->ordered()->with('translations');
                            },
                        ]);
                    },
                ])
                ->get();
        }

        return view('frontend.pages.contact.index', compact('cmsPage', 'cmsPageSections'));
    }

    public function bookDemo(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'clinic_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'bookDemo')->withInput();
        }

        $demoRequest = DemoRequest::create($request->all());

        event(new DemoRequested($demoRequest));

        return redirect()->back()->with('book_demo_submitted', __('main.book_demo_success'));
    }

    public function submitContact(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number' => 'required|string|max:50',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'contactForm')->withInput();
        }

        ContactUs::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('number'),
            'message' => $request->input('message'),
            'is_read' => false,
        ]);

        return redirect()->back()->with('contact_us_submitted', __('main.contact_us_submitted'));
    }
}