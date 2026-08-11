<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Concerns\ResolvesLoyaltyClinic;
use App\Http\Controllers\Controller;
use App\Models\LoyaltyCouponRedemption;
use App\Models\LoyaltyPointRule;
use App\Models\LoyaltyPointTransaction;
use App\Models\LoyaltyRewardCoupon;
use App\Services\LoyaltyPointsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyDashboardController extends Controller
{
    use ResolvesLoyaltyClinic;

    public function index()
    {
        $this->ensureLoyaltyEnabled();

        $clinicId = $this->loyaltyClinicId();

        $data = [
            'organization' => $this->loyaltyOrganization(),
            'rules' => LoyaltyPointRule::where('status', 1)->orderBy('points', 'desc')->get(),
            'coupons_count' => LoyaltyRewardCoupon::where('clinic_id', $clinicId)->count(),
            'active_coupons_count' => LoyaltyRewardCoupon::where('clinic_id', $clinicId)
                ->where('status', 1)
                ->whereDate('expires_at', '>=', today())
                ->count(),
            'pending_redemptions_count' => $this->forCurrentLoyaltyClinic(LoyaltyCouponRedemption::query())
                ->whereIn('status', ['pending', 'otp_sent'])
                ->count(),
            'used_redemptions_count' => $this->forCurrentLoyaltyClinic(LoyaltyCouponRedemption::query())
                ->where('status', 'used')
                ->count(),
            'points_earned' => (int) $this->forCurrentLoyaltyClinic(LoyaltyPointTransaction::query())
                ->where('type', 'earn')
                ->where('status', 1)
                ->sum('points'),
            'points_spent' => abs((int) $this->forCurrentLoyaltyClinic(LoyaltyPointTransaction::query())
                ->where('type', 'spend')
                ->where('status', 1)
                ->sum('points')),
            'recent_transactions' => $this->forCurrentLoyaltyClinic(LoyaltyPointTransaction::with('user'))
                ->latest()
                ->limit(10)
                ->get(),
            'recent_redemptions' => $this->forCurrentLoyaltyClinic(LoyaltyCouponRedemption::with(['user', 'coupon']))
                ->latest()
                ->limit(10)
                ->get(),
        ];

        return view('loyalty.index', compact('data'));
    }

    public function coupons()
    {
        $this->ensureLoyaltyEnabled();

        $clinicId = $this->loyaltyClinicId();
        $data['coupons'] = LoyaltyRewardCoupon::where('clinic_id', $clinicId)
            ->with(['redemptions' => function ($query) {
                $query->with('user')->latest();
            }])
            ->withCount(['redemptions as usage_count' => function ($query) {
                $query->whereIn('status', ['pending', 'otp_sent', 'used']);
            }])
            ->latest()
            ->paginate(10);
        $data['exchange'] = \App\Models\PointsExchange::latestActive();

        return view('loyalty.coupons', compact('data'));
    }

    public function storeCoupon(Request $request)
    {
        $this->ensureLoyaltyEnabled();

        $validated = $this->validateCouponRequest($request, true);

        LoyaltyRewardCoupon::create($validated + [
            'clinic_id' => $this->loyaltyClinicId(),
            'branch_ids' => [],
            'status' => 1,
        ]);

        session()->flash('success', trans('messages.Added'));

        return redirect()->back();
    }

    public function updateCoupon(Request $request, $id)
    {
        $this->ensureLoyaltyEnabled();

        $validated = $this->validateCouponRequest($request, false);

        $coupon = LoyaltyRewardCoupon::where('clinic_id', $this->loyaltyClinicId())->findOrFail($id);
        $coupon->update($validated);

        session()->flash('success', trans('messages.updated'));

        return redirect()->back();
    }

    public function updateCouponStatus($id, $status)
    {
        $this->ensureLoyaltyEnabled();

        $coupon = LoyaltyRewardCoupon::where('clinic_id', $this->loyaltyClinicId())->findOrFail($id);
        $coupon->update(['status' => (int) $status]);

        return response()->json(['message' => trans('messages.updated')]);
    }

    public function destroyCoupon($id)
    {
        $this->ensureLoyaltyEnabled();

        $coupon = LoyaltyRewardCoupon::where('clinic_id', $this->loyaltyClinicId())->findOrFail($id);
        $coupon->delete();

        session()->flash('success', trans('messages.deleted'));

        return redirect()->back();
    }

    public function redemptions()
    {
        $this->ensureLoyaltyEnabled();

        $data['redemptions'] = $this->forCurrentLoyaltyClinic(LoyaltyCouponRedemption::with(['user', 'coupon']))
            ->latest()
            ->paginate(15);

        return view('loyalty.redemptions', compact('data'));
    }

    public function sendRedemptionOtp($id, LoyaltyPointsService $loyaltyPointsService)
    {
        $this->ensureLoyaltyEnabled();

        $redemption = $this->forCurrentLoyaltyClinic(LoyaltyCouponRedemption::query())->findOrFail($id);
        $loyaltyPointsService->generateOtp($redemption);

        session()->flash('success', trans('main.loyalty_otp_sent'));

        return redirect()->back();
    }

    public function confirmRedemption(Request $request, $id, LoyaltyPointsService $loyaltyPointsService)
    {
        $this->ensureLoyaltyEnabled();

        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $redemption = $this->forCurrentLoyaltyClinic(LoyaltyCouponRedemption::query())->findOrFail($id);

        try {
            $loyaltyPointsService->confirmRedemption($redemption, $request->otp_code, (int) Auth::id());
            session()->flash('success', trans('main.loyalty_redemption_confirmed'));
        } catch (\Throwable $exception) {
            session()->flash('failed', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function transactions()
    {
        $this->ensureLoyaltyEnabled();

        $data['transactions'] = $this->forCurrentLoyaltyClinic(LoyaltyPointTransaction::with(['user', 'reservation']))
            ->latest()
            ->paginate(20);

        return view('loyalty.transactions', compact('data'));
    }

    private function validateCouponRequest(Request $request, bool $isCreate): array
    {
        $validated = $request->validate([
            'service_name_ar' => 'required|string|max:255',
            'service_name_en' => 'nullable|string|max:255',
            'details_ar' => 'nullable|string',
            'details_en' => 'nullable|string',
            'price_before_discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                $request->discount_type === 'percentage' ? 'max:100' : 'max:999999',
            ],
            'usage_limit' => 'nullable|integer|min:0',
            'expires_at' => $isCreate ? 'required|date|after_or_equal:today' : 'required|date',
        ]);

        $priceBefore = (float) $validated['price_before_discount'];
        $discountValue = (float) $validated['discount_value'];

        if ($validated['discount_type'] === 'fixed' && $discountValue > $priceBefore) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'discount_value' => [trans('main.loyalty_discount_exceeds_price')],
            ]);
        }

        $validated['usage_limit'] = (int) ($validated['usage_limit'] ?? 0);
        $validated['price_after_discount'] = LoyaltyRewardCoupon::calculatePriceAfterDiscount(
            $priceBefore,
            $validated['discount_type'],
            $discountValue
        );

        return $validated;
    }
}
