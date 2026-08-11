<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddTitle;
use App\Http\Requests\Admin\CreateDepartmentShiftRequest;
use App\Models\AppType;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class DepartmentShiftsController extends Controller
{
    // show department shifts
    function index($department_id)
    {
        $department = AppType::where('id',$department_id)->first();
        return view('departments.shifts', compact('department'));
    }

    // add department_shift
    public function add_department_shift($department_id, CreateDepartmentShiftRequest $request)
    {
        $auth_app = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;

        $data = $request->all();
        $data['account_type'] = $department_id;
        $data['clinic_id'] = $auth_app;
        $add_shift = Shift::create($data);
        if ($add_shift) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit department_shift
    public function edit_department_shift($id, CreateDepartmentShiftRequest $request)
    {
        $edit_shift = Shift::where('id', $id)->first();
        $data = $request->all();
        $edit_shift->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }

    public function update_status_department_shift($id, $status)
    {
        $status_shift = Shift::where('id', $id)->first();
        $status_shift->status = $status;
        $status_shift->save();
        session()->flash('success', trans('messages.update_status'));
    }

    // delete department_shift
    function destroy_department_shift($id)
    {
        Shift::where('id', $id)->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }
}
