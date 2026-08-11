<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function terms()
    {
        $setting = Setting::where('settings_type', 'terms')
            ->where('app_type', 1)
            ->select('id', 'title_' . $this->lang() . ' as title', 'content_' . $this->lang() . ' as content')
            ->first();

        return view('welcome', compact('setting'));
    }
}
