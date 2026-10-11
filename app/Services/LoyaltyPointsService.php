<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\LoyaltyCouponRedemption;
use App\Models\LoyaltyPointRule;
use App\Models\LoyaltyPointTransaction;
use App\Models\LoyaltyRewardCoupon;
use App\Models\PointsExchange;
use App\Models\User;
use App\Models\UserPoints;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoyaltyPointsService
{
    public function balance(int $userId): int
    {
        $loyaltyBalance = (int) LoyaltyPointTransaction::where('user_id', $userId)
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->sum('points');

        $legacyBalance = (int) UserPoints::where('user_id', $userId)
            ->where('status', 1)
            ->sum('point');

        return $loyaltyBalance + $legacyBalance;
    }

    public function award(User $user, string $ruleKey, array $meta = []): ?LoyaltyPointTransaction
    {
        $clinicId = $meta['clinic_id'] ?? null;
        if ($clinicId && !Clinic::isLoyaltyEnabledForClinic((int) $clinicId)) {
            return null;
        }

        $rule = LoyaltyPointRule::where('key', $ruleKey)->where('status', 1)->first();

        if (!$rule || $rule->points <= 0) {
            return null;
        }

        if (!empty($meta['source_type']) && !empty($meta['source_id'])) {
            $exists = LoyaltyPointTransaction::where('user_id', $user->id)
                ->where('source_type', $meta['source_type'])
                ->where('source_id', $meta['source_id'])
                ->where('rule_key', $ruleKey)
                ->exists();

            if ($exists) {
                return null;
            }
        }

        if ($rule->max_per_day) {
            $todayCount = LoyaltyPointTransaction::where('user_id', $user->id)
                ->where('rule_key', $ruleKey)
                ->whereDate('created_at', today())
                ->count();

            if ($todayCount >= $rule->max_per_day) {
                return null;
            }
        }

        return LoyaltyPointTransaction::create([
            'user_id' => $user->id,
            'clinic_id' => $meta['clinic_id'] ?? null,
            'reservation_id' => $meta['reservation_id'] ?? null,
            'source_type' => $meta['source_type'] ?? null,
            'source_id' => $meta['source_id'] ?? null,
            'rule_key' => $ruleKey,
            'type' => 'earn',
            'points' => $rule->points,
            'description_ar' => $meta['description_ar'] ?? $rule->name_ar,
            'description_en' => $meta['description_en'] ?? $rule->name_en,
            'expires_at' => now()->addMonths($rule->expires_after_months),
            'status' => 1,
        ]);
    }

    public function redeem(User $user, LoyaltyRewardCoupon $coupon): LoyaltyCouponRedemption
    {
        if ($coupon->status != 1 || $coupon->expires_at->lt(today())) {
            throw new \RuntimeException(trans('messages.loyalty.coupon_not_available'));
        }

        $alreadyRedeemed = LoyaltyCouponRedemption::where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'otp_sent', 'used', 'expired'])
            ->exists();

        if ($alreadyRedeemed) {
            throw new \RuntimeException(trans('messages.loyalty.coupon_already_requested'));
        }

        $exchange = PointsExchange::latestActive();

        if (! $exchange) {
            throw new \RuntimeException(trans('messages.loyalty.points_exchange_not_configured'));
        }

        $pointsCost = $coupon->pointsCost($exchange);

        if ($pointsCost <= 0) {
            throw new \RuntimeException(trans('messages.loyalty.coupon_not_available'));
        }

        if ($this->balance($user->id) < $pointsCost) {
            throw new \RuntimeException(trans('messages.loyalty.not_enough_points'));
        }

        return DB::transaction(function () use ($user, $coupon, $pointsCost) {
            $lockedCoupon = LoyaltyRewardCoupon::where('id', $coupon->id)->lockForUpdate()->first();

            if (!$lockedCoupon || $lockedCoupon->status != 1 || $lockedCoupon->expires_at->lt(today())) {
                throw new \RuntimeException(trans('messages.loyalty.coupon_not_available'));
            }

            $alreadyRedeemed = LoyaltyCouponRedemption::where('coupon_id', $lockedCoupon->id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'otp_sent', 'used', 'expired'])
                ->exists();

            if ($alreadyRedeemed) {
                throw new \RuntimeException(trans('messages.loyalty.coupon_already_requested'));
            }

            if ($lockedCoupon->usage_limit > 0) {
                $usageCount = LoyaltyCouponRedemption::where('coupon_id', $lockedCoupon->id)
                    ->whereIn('status', ['pending', 'otp_sent', 'used'])
                    ->count();

                if ($usageCount >= $lockedCoupon->usage_limit) {
                    throw new \RuntimeException(trans('messages.loyalty.coupon_usage_limit_reached'));
                }
            }

            $redemption = LoyaltyCouponRedemption::create([
                'coupon_id' => $lockedCoupon->id,
                'user_id' => $user->id,
                'clinic_id' => $lockedCoupon->clinic_id,
                'code' => strtoupper(Str::random(10)),
                'points_spent' => $pointsCost,
                'status' => 'pending',
            ]);

            LoyaltyPointTransaction::create([
                'user_id' => $user->id,
                'clinic_id' => $lockedCoupon->clinic_id,
                'source_type' => LoyaltyCouponRedemption::class,
                'source_id' => $redemption->id,
                'type' => 'spend',
                'points' => -1 * $pointsCost,
                'description_ar' => 'استبدال كوبون نقاط',
                'description_en' => 'Reward coupon redemption',
                'status' => 1,
            ]);

            // need to send notification with code / qr code to user that they have redeemed the coupon

            return $redemption;
        });
    }

    public function generateOtp(LoyaltyCouponRedemption $redemption): LoyaltyCouponRedemption
    {
        $otp = (string) random_int(100000, 999999);
        $redemption->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'status' => 'otp_sent',
        ]);

        $redemption->loadMissing('user');
        if ($redemption->user && $redemption->user->phone) {
            // try {
            //     VerificationCode::verificationCode($redemption->user->phone, $otp, 'loyalty_coupon');
            // } catch (\Throwable $exception) {
            //     report($exception);
            // }
        }

        return $redemption;
    }

    public function confirmRedemption(LoyaltyCouponRedemption $redemption, string $otp, int $confirmedByClinicId): LoyaltyCouponRedemption
    {
        if (!in_array($redemption->status, ['pending', 'otp_sent'], true)) {
            throw new \RuntimeException(trans('messages.loyalty.redemption_not_confirmable'));
        }

        if (!$redemption->otp_code || $redemption->otp_code !== $otp) {
            throw new \RuntimeException(trans('messages.loyalty.invalid_otp'));
        }

        if ($redemption->otp_expires_at && $redemption->otp_expires_at->lt(now())) {
            throw new \RuntimeException(trans('messages.loyalty.otp_expired'));
        }

        $redemption->update([
            'status' => 'used',
            'used_at' => now(),
            'confirmed_by' => $confirmedByClinicId,
        ]);

        return $redemption->fresh();
    }
}
