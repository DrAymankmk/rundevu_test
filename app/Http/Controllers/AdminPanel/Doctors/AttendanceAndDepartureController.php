<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Models\AppType;
use App\Models\Clinic;
use App\Models\PermissionsRequests;
use App\Models\Shift;
use App\Models\ShiftEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AttendanceAndDepartureController extends Controller
{


    function attendance_departure(Request $request)
    {
        $id = Auth::user()->id;
        $data['date_from'] = Session::put('date_from', $request->date_from);
        $data['date_to'] = Session::put('date_to', $request->date_to);
        $employee = Clinic::where('id', $id)->select('id', 'name', 'image', 'phone', 'email')->first();
        $query = ShiftEmployee::where('employee_id', $id)->select('id', 'dateA', 'check_in', 'check_out', 'status', 'checkin_another_employee', 'checkout_another_employee');

        if ($request->date_from) {
            $query->whereBetween('created_at', array($request->date_from, $request->date_to));
        }
        $data['attendance_departure'] = $query->paginate();

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

        return view('doctors.attendance.index', compact('data'));
    }


    // show social_media
    function employee_permissions(Request $request, $employee_id)
    {
        $data['permissions'] = PermissionsRequests::where('permission_owner', $employee_id)->select('id', 'dateA', 'permission_type', 'reason', 'status')->paginate(10);
        return view('doctors.attendance.permissions', compact('data'));
    }

    // show employee shifts
    function employee_shifts()
    {
        $employee_id = Auth::user()->id;
        $employee = Clinic::with('employee_shifts')->where('id', $employee_id)->first();
        $employee_shifts = ShiftEmployee::where('employee_id', $employee_id)->whereMonth('dateA',date('m'))->whereYear('dateA',date('Y'))->get();
        return view('doctors.shifts.index', compact('employee','employee_shifts'));
    }
}
