<?php

namespace App\Http\Controllers\AdminPanel\Clinic;

use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperVisorController extends Controller
{
    function index()
    {
        $auth_app = Auth::user()->id;
        $all_supervisor = Clinic::where('parent_id', $auth_app)
            ->where('id', '!=', Auth::user()->id)
            ->where('app_type', 11)
            ->orderBy('id', 'desc')
            ->get();
        return view('clinic_supervisor.index', compact('all_supervisor'));
    }

    function create_supervisor()
    {
        return view('clinic_supervisor.add');
    }

    function AddSupervisor(Request $request)
    {
        $auth_app = Auth::user()->id;

        $check_email = Clinic::where('phone', $request->phone)
            ->orWhere('email', $request->email)
            ->first();
        if ($check_email) {
            session()->flash('success', __(trans('admin.account_exists')));
            return redirect()->back();
        }

        $data = $request->all();
        $data['app_type'] = 11;
        $data['parent_id'] = $auth_app;
        $data['password'] = Hash::make($request->password);
        $data['jwt_token'] = Str::random(75);

        $add_account = Clinic::create($data);
        if ($add_account) {
            session()->flash('success', __(trans('admin.add_supervisor_success')));
        } else {
            session()->flash('success', __(trans('messages.profile.missing_details')));
        }
        return redirect()->route('clinic.supervisor');
    }

    function supervisor_update($id)
    {
        $supervisor = Clinic::where('id', $id)->first();
        return view('clinic_supervisor.edit', compact('supervisor'));
    }

    public function UpdateStatusSuper($id, $status)
    {
        $supervisor = Clinic::where('id', $id)->first();
        $supervisor->status = $status;
        $supervisor->save();
        session()->flash('success', __('تم تغيير حساب المشرف بنجاح'));
        return redirect()->back();
    }

    public function EditAccountSupervisor($id, Request $request)
    {
        $supervisor = Clinic::whereId($id)->first();
        $supervisor->name = $request->name;
        $supervisor->email = $request->email;
        $supervisor->phone = $request->phone;
        if (!empty($request->image)) {
            $supervisor->image = $request->image;
        }
        $supervisor->save();
        session()->flash('success', __('تم تعديل بيانات المشرف بنجاح'));
        return redirect()->route('clinic.supervisor');
    }

    function destroyAccount($id)
    {
        Clinic::where('id', $id)->delete();
        session()->flash('success', __('تم حذف  حساب المشرف بنجاح'));
        return redirect()->back();
    }
}
