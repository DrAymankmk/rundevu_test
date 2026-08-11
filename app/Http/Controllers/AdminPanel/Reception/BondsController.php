<?php

namespace App\Http\Controllers\AdminPanel\Reception;

use App\Http\Controllers\Controller;
use App\Models\Bonds;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;

class BondsController extends Controller
{
    // bonds
    function bonds($patient_id = null)
    {
        $data['doctors'] = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 3)->get();
        $reception_ids = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 2)->pluck('id');
        $data['patients'] = User::whereIn('reception_id', $reception_ids)->orderBy('id', 'desc')->get();
        $query = Bonds::whereIn('reception_id',$reception_ids)->orderBy('id','desc');
        if ($patient_id) {
            $query->where('user_id', $patient_id);
        }
        $data['bonds'] = $query->get();

        return view('reception.bonds', compact('data'));
    }

    function create_bond(Request $request)
    {
        $data = $request->all();
        $data['reception_id'] = auth()->user()->id;
        $data['user_id'] = $request->patient_id;
        $create_reservation = Bonds::create($data);
        if ($create_reservation) {
            session()->flash('success', __(trans('admin.create_bond_success')));
        }
        return redirect()->back();
    }
}
