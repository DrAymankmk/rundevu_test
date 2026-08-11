<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserApp\Coupons\CouponsResource;
use App\Http\Resources\UserApp\MedicalReport\MedicalReportResource;
use App\Models\Coupon;
use App\Models\MedicalReport;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class CouponController extends APIController
{
    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    // coupons
    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $query = Coupon::select('id', 'title_' . $this->lang . ' as title','coupon_number')->paginate(10);
        $coupon_list = CouponsResource::collection($query)->response()->getData();
        return $this->success(trans('user.data'), $coupon_list);
    }
}
