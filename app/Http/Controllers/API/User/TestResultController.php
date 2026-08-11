<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\UserApp\TestResult\TestResultDetailsResource;
use App\Http\Resources\UserApp\TestResult\TestResultResource;
use App\Models\PatientService;
use App\Models\TestResult;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class TestResultController extends APIController
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
        $query = PatientService::with('services')->where('user_id', $user_id)->select('id', 'clinic_id', 'doctor_id','created_at','service_id')->where('type',$request->type)->orderBy('id','desc')->paginate(20);
        $test_result_list = TestResultResource::collection($query)->response()->getData();
        return $this->success(trans('user.data'), $test_result_list);
    }

    // test result
    function details(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $query = PatientService::where('id',$request->test_result_id)->first();
        $test_result_details = new TestResultDetailsResource($query);
        return $this->success(trans('user.data'), $test_result_details);
    }
}
