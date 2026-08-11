<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyCouponRedemption extends Model
{
    protected $fillable = [
        'coupon_id',
        'user_id',
        'clinic_id',
        'code',
        'otp_code',
        'points_spent',
        'status',
        'otp_expires_at',
        'used_at',
        'confirmed_by',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function coupon()
    {
        return $this->belongsTo(LoyaltyRewardCoupon::class, 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
