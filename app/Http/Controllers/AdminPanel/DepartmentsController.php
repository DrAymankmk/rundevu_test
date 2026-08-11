<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddDepartment;
use App\Models\AppType;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

class DepartmentsController extends Controller
{
    // show departments

    public function index()
    {
        $user = Auth::user();

        $check_authorization = $user->app_type == 7
            ? $user->id
            : $user->parent_id;

        $data['departments'] = AppType::whereIn('id', [2, 3])
            ->withCount('doctors') // 👈 عدد الدكاترة
            ->orderByDesc('id')
            ->paginate(20);

        return view('departments.index', compact('data'));
    }

    // add department
    public function add_department(AddDepartment $request)
    {
        $auth_app = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        $data = $request->all();
        $data['type'] = 2;
        $data['clinic_id'] = $auth_app;
        $add_depart = AppType::create($data);
        if ($add_depart) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit city
    public function edit_department($id, AddDepartment $request)
    {
        $edit_department = AppType::where('id', $id)->first();
        $data = $request->all();
        $edit_department->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }

    public function update_status_department($id, $status)
    {
        $status_department = AppType::where('id', $id)->first();
        $status_department->status = $status;
        $status_department->save();
        session()->flash('success', trans('messages.department.change_status'));
    }


    // delete department
    function destroy_department($id)
    {
        $department = AppType::where('id', $id)->first();
        $department->delete();
        session()->flash('success', trans('messages.delete'));
        return redirect()->back();
    }

}
