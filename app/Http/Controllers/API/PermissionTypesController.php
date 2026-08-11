<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clinics\ShiftEmployeeRequest;
use App\Http\Requests\Clinics\UpdateOrCreateEmployeeShiftRequest;
use App\Http\Requests\PermissionTypes\PermissionRequest;
use App\Http\Resources\CitiesResource;
use App\Http\Resources\EmployeeShiftResource;
use App\Models\Clinic;
use App\Models\PermissionsRequests;
use App\Models\PermissionsType;
use App\Models\ShiftEmployee;
use App\Repositories\App\MainRepository;
use Illuminate\Http\Request;

class PermissionTypesController extends APIController
{

    public $repository;

    function __construct(Request $request, MainRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    // get permission type
    function permission_types(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $permissions = PermissionsType::select('id', 'name_' . $this->lang . ' as name')->get();
        $permissions_list = CitiesResource::collection($permissions)->response()->getData();
        return $this->success(trans('messages.permissions.all'), $permissions_list);
    }

    // create permission and send to clinic
    public function permission_request(PermissionRequest $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $data = $request->all();
        $data['dateA'] = date('Y-m-d');
        $data['permission_owner'] = $check_authorization->id;
        $data['clinic_id'] = $check_authorization->parent_id;
        $data['permission_type'] = $request->permission_id;
        $create_permission = PermissionsRequests::create($data);
        if ($create_permission) {
            return $this->respondWithMessage(trans('messages.permissions.send_permission'));
        } else {
            $this->respondWithError(trans('messages.something_went_wrong'));
        }
    }
}
