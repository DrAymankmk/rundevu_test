<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\AppType;
use App\Models\AttendanceSetting;
use App\Models\Clinic;
use App\Models\PermissionsRequests;
use App\Models\ShiftEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AttendanceAndDepartureController extends Controller
{
    // show attendance_departure
    function attendance_departure(Request $request)
    {
        $check_authorization = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;

//        $check_authorization = Auth::user();
        $data['departments'] = AppType::where(function ($q) use ($check_authorization) {
            $q->whereIn('id', [2, 3, 4, 5]);
            $q->orwhere('clinic_id', $check_authorization);
        })->orderBy('id', 'asc')->get();


        $query = ShiftEmployee::where('clinic_id',$check_authorization)->groupBy('employee_id')->orderBy('account_type', 'asc');
        if ($request->date_from) {
            $query->whereBetween('created_at', array($request->date_from, $request->date_to));
        }
        if ($request->department_id) {
            $query->where('account_type', $request->department_id);
        }
        $data['employees'] = $query->paginate(10);
        $data['date_from'] = Session::put('date_from', $request->date_from);
        $data['date_to'] = Session::put('date_to', $request->date_to);
        $data['employer_id'] = Session::put('department_id', $request->department_id);

        return view('attendance.employees', compact('data'));
    }

    function view_employee(Request $request, $id)
    {
        $date_from = Session::get('date_from');
        $date_to = Session::get('date_to');
        $employee = Clinic::where('id', $id)->select('id', 'name', 'image', 'phone', 'email')->first();
        $data['attendance_departure'] = ShiftEmployee::where('employee_id', $id)->whereBetween('created_at', array($date_from, $date_to))->select('id', 'dateA', 'check_in', 'check_out', 'status', 'checkin_another_employee', 'checkout_another_employee')->paginate(10);
        foreach ($data['attendance_departure'] as $attendance) {
            if ($attendance->status == 0) {
                $attendance->audience = trans('messages.attendance.official_vacation');
                $attendance->leave = trans('messages.attendance.official_vacation');
            } elseif (empty($attendance->check_in) && empty($attendance->check_out)) {
                $attendance->audience = trans('messages.attendance.absence');
                $attendance->leave = trans('messages.attendance.absence');
            } else {
                $attendance->audience = !empty($attendance->check_in) ? date('h:i', strtotime($attendance->check_in)) : "";
                $attendance->leave = !empty($attendance->check_out) ? date('h:i', strtotime($attendance->check_out)) : "";
            }
        }
        $data['employee'] = $employee;

        return view('attendance.view_employee', compact('data'));
    }


    // show social_media
    function employee_permissions(Request $request, $employee_id)
    {
        $query = PermissionsRequests::select('id', 'dateA', 'permission_type', 'reason', 'status','permission_owner','clinic_id');
        if ($employee_id == 0) {
            $query->where('clinic_id',Auth::user()->parent_id);
        } else {
            $query->where('permission_owner',$employee_id);
        }
        $data['permissions'] = $query->paginate(10);
//        $data['permissions'] = PermissionsRequests::where('permission_owner',$employee_id)->select('id', 'dateA', 'permission_type', 'reason', 'status')->paginate(10);
        return view('attendance.permissions', compact('data'));
    }

    public function update_status_permission($id, $status)
    {
        $status_social = PermissionsRequests::where('id',$id)->first();
        if ($status == 1) {
            $permission_accept = ShiftEmployee::where(['employee_id' => $status_social->permission_owner, 'dateA' => $status_social->dateA])->first();
            if ($permission_accept)  {
                $permission_accept->total_delay_minute = null;
                $permission_accept->save();
            }

        }
        $status_social->status = $status;
        $status_social->save();
        session()->flash('success', trans('admin.change_status_permission'));
        return redirect()->back();
    }


    //  get attendance setting
    function attendance_setting()
    {
        if (Auth::user()->app_type == 3 || Auth::user()->app_type == 7) {
            $auth_app = Auth::user()->id;
        } else {
            $auth_app =  Auth::user()->parent_id;
        }
        $setting = AttendanceSetting::where('clinic_id', $auth_app)->first();
        return view('attendance.setting', compact('setting','auth_app'));
    }


    public function add_attendance_setting($id, Request $request)
    {
        $data =  $request->all();
        if ($id == 0) {
            $data['clinic_id'] = $request->clinic_id;
            $created = AttendanceSetting::create($data);
        } else {
            $add_attendance_setting = AttendanceSetting::whereId($id)->first();
            $add_attendance_setting->update($data);
        }

        session()->flash('success', trans('admin.updated'));
        return redirect()->back();
    }
}
