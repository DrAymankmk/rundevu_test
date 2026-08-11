<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\ClinicModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // login form
    function form_login()
    {
        return view('login');
    }

    public function login(Request $request, ClinicModuleService $modules)
    {
        $credential = array('email' => $request->email, 'password' => $request->password);
        if ($user = Auth::attempt($credential)) {
            Session::flash('loggedin', '1');
            Session::put('permission', Auth::user());
            if ((Auth::user()->status == 0)) {
                session()->flash('failed',trans('messages.auth.account_suspended'));
                return redirect()->back();
            }

            if (Auth::user()->app_type == 1 && empty(Auth::user()->parent_id)) {
                Auth::user()->update([
                    'parent_id' => Auth::id(),
                ]);

                Session::put('permission', Auth::user()->fresh());
            }

            $redirectRoute = $modules->loginRedirectRoute(Auth::user());
            Session::put('enabled_modules', $modules->getEnabledModules(Auth::user()));

            return redirect()->route($redirectRoute);

        } else {
           session()->flash('failed', trans('messages.auth.login_message_failed'));
            return redirect()->back();
        }
    }

    // logout
    public function logout()
    {
//        Auth::logout();
        Session::forget('frontSession');
        return redirect('/admin/login');
    }
}
