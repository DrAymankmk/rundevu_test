<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PointsResource;
use App\Http\Resources\UserApp\ElectronicPaymentResource;
use App\Models\ClinicPoint;
use App\Models\ElectronicPayment;
use App\Models\PointsExchange;
use App\Models\UserPoints;
use App\Repositories\App\UserRepository;
use App\Services\LoyaltyPointsService;
use Illuminate\Http\Request;

class PointsController extends APIController
{
    public $repository;
    private $pointsService;

    function __construct(Request $request, UserRepository $repository, LoyaltyPointsService $pointsService)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
        $this->pointsService = $pointsService;
    }

    function points(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $points = UserPoints::where('user_id', $check_authorization->id)->orderBy('id', 'desc')->paginate(10);
        $points_list = PointsResource::collection($points)->response()->getData();
        $data['points'] = $this->pointsService->balance($check_authorization->id);
        $data['subtotal_points'] = $data['points'];
        $points_exchange = PointsExchange::where('status',1)->select('id','points','price')->get();
        $point_total = collect(['info' => $data,'points_exchange'=>$points_exchange]);
        $result = $point_total->merge($points_list);
        return $this->success(trans('messages.points.all'), $result);
    }

    // electronic payment
    function electronic_payment(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $user_id = $request->user_id ?? $check_authorization->id;
        $query = ElectronicPayment::where('user_id', $user_id)->select('id', 'user_id', 'clinic_id', 'doctor_id', 'created_at', 'type', 'amount')->paginate(20);
        $electronic_payment = ElectronicPaymentResource::collection($query)->response()->getData();
        return $this->success('Electronic Payment', $electronic_payment);
    }
}
