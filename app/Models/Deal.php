<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_NOT_ACTIVE = 'not_active';

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'conditions_ar',
        'conditions_en',
        'image',
        'clinic_id',
        'specialty_id',
        'price_before',
        'price_after',
        'status',
        'maximum_number_of_usage',
        'number_of_uses',
        'expiration_date',
    ];

    protected $casts = [
        'price_before' => 'decimal:2',
        'price_after' => 'decimal:2',
        'expiration_date' => 'date',
        'maximum_number_of_usage' => 'integer',
        'number_of_uses' => 'integer',
    ];

    public function getImageAttribute($value)
    {
        return $value ? asset('media/deals/' . $value) : null;
    }

    public function setImageAttribute($value)
    {
        if (is_string($value) || $value === null) {
            $this->attributes['image'] = $value;
            return;
        }

        if ($value) {
            $imageName = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/deals/'), $imageName);
            $this->attributes['image'] = $imageName;
        }
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    public function patientDeals()
    {
        return $this->hasMany(PatientDeal::class, 'deal_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('expiration_date')
                    ->orWhereDate('expiration_date', '>=', Carbon::today()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('maximum_number_of_usage')
                    ->orWhereColumn('number_of_uses', '<', 'maximum_number_of_usage');
            });
    }

    public function isAvailable(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->expiration_date && $this->expiration_date->lt(Carbon::today())) {
            return false;
        }

        if ($this->maximum_number_of_usage !== null && $this->number_of_uses >= $this->maximum_number_of_usage) {
            return false;
        }

        return true;
    }

    public function localizedTitle(?string $lang = null): string
    {
        $lang = $lang ?: app()->getLocale();

        return $lang === 'en' ? ($this->title_en ?: $this->title_ar) : ($this->title_ar ?: $this->title_en);
    }

    public function localizedDescription(?string $lang = null): ?string
    {
        $lang = $lang ?: app()->getLocale();

        return $lang === 'en' ? ($this->description_en ?: $this->description_ar) : ($this->description_ar ?: $this->description_en);
    }

    public function localizedConditions(?string $lang = null): ?string
    {
        $lang = $lang ?: app()->getLocale();

        return $lang === 'en' ? ($this->conditions_en ?: $this->conditions_ar) : ($this->conditions_ar ?: $this->conditions_en);
    }

    public static function deactivateExpired($date = null): int
    {
        $date = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

        return static::where('status', self::STATUS_ACTIVE)
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '<', $date)
            ->update(['status' => self::STATUS_NOT_ACTIVE]);
    }
}
