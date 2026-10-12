<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyRewardCoupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'service_name_ar',
        'service_name_en',
        'details_ar',
        'details_en',
        'price_before_discount',
        'discount_type',
        'discount_value',
        'price_after_discount',
        'usage_limit',
        'expires_at',
        'branch_ids',
        'show_on_mobile_home',
        'status',
    ];

    protected $casts = [
        'branch_ids' => 'array',
        'show_on_mobile_home' => 'boolean',
        'expires_at' => 'date',
        'price_before_discount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'price_after_discount' => 'decimal:2',
    ];

    public static function calculatePriceAfterDiscount(float $priceBefore, string $discountType, float $discountValue): float
    {
        if ($discountType === 'percentage') {
            $after = $priceBefore - ($priceBefore * $discountValue / 100);
        } else {
            $after = $priceBefore - $discountValue;
        }

        return max(0, round($after, 2));
    }

    public function discountCost(): float
    {
        return max(0, round((float) $this->price_before_discount - (float) $this->price_after_discount, 2));
    }

    public function pointsCost(?PointsExchange $exchange = null): int
    {
        $discountCost = $this->discountCost();

        if ($discountCost <= 0) {
            return 0;
        }

        $exchange ??= PointsExchange::latestActive();

        if (! $exchange) {
            return 0;
        }

        return $exchange->pointsForPrice($discountCost);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function applicableBranches()
    {
        $ids = array_values(array_filter(array_map('intval', $this->branch_ids ?? [])));

        if ($ids === []) {
            return collect();
        }

        return Clinic::with('city')
            ->whereIn('id', $ids)
            ->orderBy('name')
            ->get();
    }

    public function appliesToAllBranches(): bool
    {
        return empty($this->branch_ids);
    }

    /**
     * City ids covered by this coupon (from selected branches, or all org branches / clinic city).
     *
     * @return array<int, int>
     */
    public function cityIds(): array
    {
        $clinic = $this->clinic ?? Clinic::find($this->clinic_id);

        if (! $clinic) {
            return [];
        }

        if (! $this->appliesToAllBranches()) {
            return $this->applicableBranches()
                ->pluck('city_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();
        }

        if ((int) $clinic->app_type === 7) {
            return $clinic->city_id ? [(int) $clinic->city_id] : [];
        }

        $branchCityIds = Clinic::where('parent_id', $clinic->id)
            ->where('app_type', 7)
            ->where('status', 1)
            ->whereNotNull('city_id')
            ->pluck('city_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($clinic->city_id) {
            $branchCityIds[] = (int) $clinic->city_id;
        }

        return array_values(array_unique($branchCityIds));
    }

    public function notificationUrl(): string
    {
        return 'loyalty_coupon/' . $this->id;
    }

    public function redemptions()
    {
        return $this->hasMany(LoyaltyCouponRedemption::class, 'coupon_id');
    }

    public function usageCount(): int
    {
        if ($this->relationLoaded('redemptions')) {
            return $this->redemptions
                ->whereIn('status', ['pending', 'otp_sent', 'used'])
                ->count();
        }

        return $this->redemptions()
            ->whereIn('status', ['pending', 'otp_sent', 'used'])
            ->count();
    }

    public function remainingUsage(): ?int
    {
        if ((int) $this->usage_limit <= 0) {
            return null;
        }

        return max(0, (int) $this->usage_limit - $this->usageCount());
    }

    public function spendTransactions()
    {
        if ($this->relationLoaded('redemptions')) {
            $redemptionIds = $this->redemptions->pluck('id');
        } else {
            $redemptionIds = $this->redemptions()->pluck('id');
        }

        if ($redemptionIds->isEmpty()) {
            return LoyaltyPointTransaction::query()->whereRaw('1 = 0');
        }

        return LoyaltyPointTransaction::query()
            ->where('source_type', LoyaltyCouponRedemption::class)
            ->whereIn('source_id', $redemptionIds)
            ->with('user')
            ->latest();
    }
}
