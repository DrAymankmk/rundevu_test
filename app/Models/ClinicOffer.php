<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class ClinicOffer extends Model
{
    use HasFactory,SoftDeletes;
    public $fillable = ['clinic_id','specialty_id', 'title_ar', 'title_en', 'image', 'discount','start_date','end_date','status'];

    public static function deleteExpired($date = null): int
    {
        $date = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

        return static::whereNotNull('end_date')
            ->whereDate('end_date', '<=', $date)
            ->delete();
    }

    public function getImageAttribute($value)
    {
        return $value ? asset('media/offers/' . $value) : null;
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            $imageName = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/offers/'), $imageName);
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
}
