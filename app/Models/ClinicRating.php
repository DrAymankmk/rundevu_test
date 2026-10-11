<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicRating extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id', 'user_id', 'rating_id', 'comment','rate_value','status'];


    function users () {
        return $this->belongsTo(User::class,'user_id');
    }

    public static function rate($clinic_id)
    {
        $doctorIds = Clinic::where('parent_id', $clinic_id)
            ->where('app_type', 3)
            ->where('status', 1)
            ->pluck('id');

        if ($doctorIds->isEmpty()) {
            return (string) 0;
        }

        $doctorRates = ReservationRate::query()
            ->whereIn('doctor_id', $doctorIds)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->whereNotNull('rate_value')
            ->selectRaw('doctor_id, AVG(rate_value) as doctor_rate')
            ->groupBy('doctor_id')
            ->get();

        if ($doctorRates->isEmpty()) {
            return (string) 0;
        }

        return (string) round((float) $doctorRates->avg('doctor_rate'), 1);
    }

    public static function rateCount($clinic_id): int
    {
        $doctorIds = Clinic::where('parent_id', $clinic_id)
            ->where('app_type', 3)
            ->where('status', 1)
            ->pluck('id');

        if ($doctorIds->isEmpty()) {
            return 0;
        }

        return ReservationRate::query()
            ->whereIn('doctor_id', $doctorIds)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->whereNotNull('rate_value')
            ->count();
    }
}
