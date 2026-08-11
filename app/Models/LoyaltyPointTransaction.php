<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPointTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'clinic_id',
        'reservation_id',
        'source_type',
        'source_id',
        'rule_key',
        'type',
        'points',
        'description_ar',
        'description_en',
        'expires_at',
        'expired_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservations::class);
    }
}
