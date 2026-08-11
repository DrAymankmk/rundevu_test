<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Models\City;
use App\Models\Clinic;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperVisorController extends Controller
{
    // get all supervisor
    function index()
    {
        $auth_app = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        $data['supervisors'] = Clinic::where('parent_id', $auth_app)->where('id', '!=', Auth::user()->id)->where('app_type', 6)->orderBy('id', 'desc')->get();
        $data['cities'] = City::where('status', 1)->latest()->get();
        $data['roles'] = Role::where('status', 1)->latest()->get();
        return view('main_admin.supervisor.index', compact('data'));
    }


    // add supervisor in main admin
    function create_supervisor(Request $request)
    {
        $auth_app =  Auth::user()->parent_id;
//        $request->validate([
//            'permissions' => 'required|min:1'
//        ]);
        $check_email = Clinic::where('phone', $request->phone)->orwhere('email', $request->email)->first();
        if ($check_email) {
            session()->flash('success', __(trans('admin.account_exists')));
            return redirect()->back();
        }
        $data = $request->all();
        $data['app_type'] = 6;
        $data['parent_id'] = $auth_app;
        $data['password'] = Hash::make($request->password);
        $data['jwt_token'] = Str::random(75);
        $add_account = Clinic::create($data);
//        $add_account->givePermissionTo($request->permissions);
        if ($add_account) {
            session()->flash('success', __(trans('admin.add_supervisor_success')));
        } else {
            session()->flash('success', __(trans('messages.profile.missing_details')));
        }
        return redirect()->back();

    }

    public function update_supervisor($id, Request $request)
    {
        $data = $request->all();
        $supervisor = Clinic::whereId($id)->first();
        $update_supervisor = $supervisor->update($data);
        session()->flash('success', __('تم تعديل بيانات المشرف بنجاح'));
        return redirect()->back();
    }

    function destroyAccount($id)
    {
        Clinic::where('id', $id)->delete();
        session()->flash('success', __('تم حذف  حساب المشرف بنجاح'));
        return redirect()->back();
    }
}
