<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionTypes\PermissionRequest;
use App\Models\PermissionsRequests;
use App\Models\PermissionsType;
use Illuminate\Support\Facades\Auth;

class PermissionsController extends Controller
{

    function index()
    {
        $data['permissions_types'] = PermissionsType::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name', 'status')->get();
        return view('doctors.permissions.add', compact('data'));
    }

    // send request permission
    function send_request_permission(PermissionRequest $request)
    {
        $data = $request->all();
        $data['dateA'] = date('Y-m-d');
        $data['permission_owner'] = Auth::user()->id;
        $data['clinic_id'] = Auth::user()->parent_id;
        $data['permission_type'] = $request->permission_id;
        $create_permission = PermissionsRequests::create($data);
        if ($create_permission) {
            session()->flash('success', trans('messages.permissions.send_permission'));
        } else {
            session()->flash('failed', trans('messages.something_went_wrong'));
        }
        return redirect()->back();
    }
}
