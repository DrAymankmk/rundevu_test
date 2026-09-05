<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicContract extends Model
{
    use HasFactory;

    public const ANNUAL_SUBSCRIPTION = 'annual_subscription';
    public const ONLINE_PAYMENT_COMMISSION = 'online_payment_commission';
    public const CASH_COMMISSION = 'cash_commission';
    public const PAYMENT_CASH = 'cash';
    public const PAYMENT_ONLINE = 'online';

    protected $fillable = [
        'clinic_id',
        'contract_model',
        'commission_rate',
        'annual_subscription_amount',
        'annual_subscription_starts_at',
        'annual_subscription_ends_at',
        'rendezvous_badge_enabled',
        'payment_method',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'annual_subscription_amount' => 'decimal:2',
        'annual_subscription_starts_at' => 'date',
        'annual_subscription_ends_at' => 'date',
        'rendezvous_badge_enabled' => 'boolean',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public static function defaultAttributes(): array
    {
        return [
            'contract_model' => self::CASH_COMMISSION,
            'commission_rate' => 0,
            'annual_subscription_amount' => null,
            'annual_subscription_starts_at' => null,
            'annual_subscription_ends_at' => null,
            'rendezvous_badge_enabled' => false,
            'payment_method' => self::PAYMENT_CASH,
        ];
    }
}
