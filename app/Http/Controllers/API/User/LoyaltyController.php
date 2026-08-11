<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Models\LoyaltyCouponRedemption;
use App\Models\LoyaltyPointRule;
use App\Models\LoyaltyPointTransaction;
use App\Models\LoyaltyRewardCoupon;
use App\Models\LoyaltyShareLog;
use App\Repositories\App\UserRepository;
use App\Services\LoyaltyPointsService;
use Illuminate\Http\Request;

class LoyaltyController extends APIController
{
    private $repository;
    private $pointsService;

    public function __construct(Request $request, UserRepository $repository, LoyaltyPointsService $pointsService)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
        $this->pointsService = $pointsService;
    }

    public function wallet(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $transactions = LoyaltyPointTransaction::with('clinic')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return $this->success('Loyalty points', [
            'balance' => $this->pointsService->balance($user->id),
            'rules' => LoyaltyPointRule::where('status', 1)->get(),
            'transactions' => $transactions,
        ]);
    }

    public function rewards(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $coupons = LoyaltyRewardCoupon::with('clinic')
            ->where('status', 1)
            ->whereDate('expires_at', '>=', now())
            ->when($request->clinic_id, fn ($q) => $q->where('clinic_id', $request->clinic_id))
            ->latest()
            ->paginate(20);

        return $this->success('Rewards store', [
            'balance' => $this->pointsService->balance($user->id),
            'coupons' => $coupons,
        ]);
    }

    public function redeem(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $coupon = LoyaltyRewardCoupon::where('status', 1)->find($request->coupon_id);
        if (!$coupon) {
            return $this->respondWithError('Coupon not found');
        }

        try {
            $redemption = $this->pointsService->redeem($user, $coupon);
        } catch (\RuntimeException $e) {
            return $this->respondWithError($e->getMessage());
        }

        return $this->success('Coupon redeemed', [
            'redemption' => $redemption,
            'balance' => $this->pointsService->balance($user->id),
        ]);
    }

    public function redemptions(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $redemptions = LoyaltyCouponRedemption::with(['coupon', 'clinic'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return $this->success('My coupons', $redemptions);
    }

    public function share(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $log = LoyaltyShareLog::create([
            'user_id' => $user->id,
            'clinic_id' => $request->clinic_id,
            'shareable_type' => $request->shareable_type,
            'shareable_id' => $request->shareable_id,
        ]);

        $this->pointsService->award($user, 'share', [
            'clinic_id' => $request->clinic_id,
            'source_type' => LoyaltyShareLog::class,
            'source_id' => $log->id,
        ]);

        return $this->success('Share recorded', [
            'balance' => $this->pointsService->balance($user->id),
        ]);
    }

    public function shareApp(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $log = LoyaltyShareLog::create([
            'user_id' => $user->id,
            'clinic_id' => null,
            'shareable_type' => 'app',
            'shareable_id' => null,
        ]);

        $transaction = $this->pointsService->award($user, 'share', [
            'source_type' => LoyaltyShareLog::class,
            'source_id' => $log->id,
            'description_ar' => 'مشاركة التطبيق',
            'description_en' => 'App share',
        ]);

        return $this->success($transaction ? 'App share points awarded' : 'App share recorded', [
            'points_earned' => $transaction ? (int) $transaction->points : 0,
            'balance' => $this->pointsService->balance($user->id),
            'max_per_day_reached' => $transaction ? false : true,
        ]);
    }
}
