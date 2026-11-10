<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDeal extends Model
{
    use HasFactory;

    public const SETTLEMENT_PENDING = 'pending';
    public const SETTLEMENT_TRANSFER_COMPLETED = 'transfer_completed';

    protected $fillable = [
        'deal_id',
        'patient_id',
        'generated_code',
        'used',
        'used_date_time',
        'used_by',
        'price_after',
        'platform_entitlement',
        'clinic_net',
        'deal_revenue_type',
        'deal_revenue_amount',
        'settlement_status',
        'settled_at',
    ];

    protected $casts = [
        'used' => 'boolean',
        'used_date_time' => 'datetime',
        'settled_at' => 'datetime',
        'price_after' => 'decimal:2',
        'platform_entitlement' => 'decimal:2',
        'clinic_net' => 'decimal:2',
        'deal_revenue_amount' => 'decimal:2',
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function usedBy()
    {
        return $this->belongsTo(Clinic::class, 'used_by');
    }

    public function scopeUsed($query)
    {
        return $query->where('used', true);
    }

    public function scopeUnused($query)
    {
        return $query->where('used', false);
    }

    public function scopePendingSettlement($query)
    {
        return $query->where('settlement_status', self::SETTLEMENT_PENDING);
    }
}
