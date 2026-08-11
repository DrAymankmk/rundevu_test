<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutUsController extends Controller
{
    // show about us
    function index($type)
    {
        if (Auth::user()->app_type == 5) {
            $app_type = 5;
        } elseif (Auth::user()->app_type == 25 || Auth::user()->app_type == 26 || Auth::user()->app_type == 10) {
            $app_type = Auth::user()->app_type;
        } else {
            $app_type = Auth::user()->app_type == 3 ? 3 : 1;
        }
        $title = $type;
        $setting = Setting::where('settings_type', $type)->where('app_type', $app_type)->select('id', 'title_' . $this->lang() . ' as title', 'content_' . $this->lang() . ' as content', 'settings_type', 'app_type')->first();
        if (Auth::user()->app_type == 3 || Auth::user()->app_type == 5 || Auth::user()->app_type == 6 || Auth::user()->app_type == 25 || Auth::user()->app_type == 26 || Auth::user()->app_type == 10) {
            return view('doctors.setting.index', compact('setting', 'title'));
        }
        return view('setting.index', compact('setting', 'title'));
    }

    function app_setting($setting_type, $app_type)
    {
        $title = $app_type;
        $all_settings = Setting::with('type')->get();
        $setting = Setting::with('type')->where('settings_type', $setting_type)->where('app_type', $app_type)->first();
        return view('main_admin.settings', compact('setting', 'title', 'all_settings'));
    }


    public function update_setting($id,Request $request)
    {
        $setting = Setting::with('type')->where('id', $id)->first();
        $data = $request->all();
        Setting::updateOrCreate(
            [
                'settings_type' => $setting->settings_type,
                'app_type' => $setting->app_type ?? null,
            ],
          $data
        );
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }
}
