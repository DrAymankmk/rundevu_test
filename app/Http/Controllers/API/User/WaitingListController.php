<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\UserApp\WaitingListResource;
use App\Models\Reservations;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class WaitingListController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    function waitingList(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $user_id = $request->user_id ?? $check_authorization->id;
        $query = Reservations::where('user_id', $user_id)->select('id','user_id', 'clinic_id', 'doctor_id','created_at')->where(['date'=>date('Y-m-d'),'payment_status'=>1])->orderBy('waiting_list')->paginate(20);
        $waiting_list = WaitingListResource::collection($query)->response()->getData();
        return $this->success(trans('user.data'), $waiting_list);
    }
}
