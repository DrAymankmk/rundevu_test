<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserApp\PharmacyPrescription\PharmacyPrescriptionDetailsResource;
use App\Http\Resources\UserApp\PharmacyPrescription\PharmacyPrescriptionResource;
use App\Http\Resources\UserApp\TestResult\TestResultDetailsResource;
use App\Http\Resources\UserApp\TestResult\TestResultResource;
use App\Models\PharmacyPrescription;
use App\Models\TestResult;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class PharmacyPrescriptionController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    // test result
    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $user_id = $request->user_id ?? $check_authorization->id;
        $query = PharmacyPrescription::where('user_id', $user_id)->select('id', 'clinic_id', 'doctor_id','created_at')->paginate(20);
        $prescriptions_list = PharmacyPrescriptionResource::collection($query)->response()->getData();
        return $this->success(trans('user.data'), $prescriptions_list);
    }

    // PharmacyPrescription
    function details(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $query = PharmacyPrescription::where('id',$request->prescription_id)->first();
        $prescription_details = new PharmacyPrescriptionDetailsResource($query);
        return $this->success(trans('user.data'), $prescription_details);
    }
}
