<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    // get points
    function index()
    {
        $data['points'] = Point::latest()->get();
        return view('main_admin.points.index', compact('data'));
    }


    // add specialty
    public function add_point(Request $request)
    {
        $data = $request->all();
        $add_point = Point::create($data);
        if ($add_point) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit specialty
    public function update_point($id, Request $request)
    {
        $edit_point = Point::where('id', $id)->first();
        $data = $request->all();
        $edit_point->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }
    // update status ClinicSpecialist


    // delete ClinicSpecialist
    function destroy_point($id)
    {
        $point = Point::where('id', $id)->first();
        $point->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }
}
