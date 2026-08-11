<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\ClinicSpecialist;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialtiesControllerController extends Controller
{
    // get specialists
    function index()
    {
        $data['specializations'] = Specialty::withCount('sub_specialties_list','clinic_specialties')->where('parent_id',null)->orderBy('id', 'desc')->get();
        return view('main_admin.specialties', compact('data'));
    }

    // get sub specialist
    function sub_specialties($specialty_id)
    {
        $specialty = Specialty::with('sub_specialties_list')->whereId($specialty_id)->first();
        return view('main_admin.subSpecialties', compact('specialty'));
    }

    // add specialty
    public function add_specialty(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $add_specialty = Specialty::create($data);
        if ($add_specialty) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit specialty
    public function update_specialty($id, Request $request)
    {
        $edit_specialty = Specialty::where('id', $id)->first();
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $edit_specialty->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }
    // update status ClinicSpecialist
    public function update_status_specialty($id, $status)
    {
        $status = (int) $status;

        if (!in_array($status, [0, 1], true)) {
            return response()->json(['message' => trans('messages.something_went_wrong')], 422);
        }

        $specialty = Specialty::where('id', $id)->first();

        if (!$specialty) {
            return response()->json(['message' => trans('messages.something_went_wrong')], 404);
        }

        $specialty->status = $status;
        $specialty->created_by = Auth::user()->id;
        $specialty->save();

        session()->flash('success', trans('messages.update_status'));

        return response()->json([
            'message' => trans('messages.update_status'),
            'status' => $specialty->status,
        ]);
    }


    // delete ClinicSpecialist
    function destroy_specialty($id)
    {
        $specialty = Specialty::find($id);

        if (!$specialty) {
            return response()->json(['status' => false, 'message' => 'التخصص غير موجود'], 404);
        }

        // تنفيذ الحذف
          $specialty->delete();

        return response()->json(['status' => true, 'message' => trans('messages.deleted')]);
    }
}
