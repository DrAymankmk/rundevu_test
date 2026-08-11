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
        $sum = 0;
        $rates = ClinicRating::where('clinic_id', $clinic_id)->where('comment','!=',null)->get();
        if (sizeof($rates) > 0) {
            foreach ($rates as $rate) {
                $sum += $rate->rate_value;
            }
            return (string)round(($sum / sizeof($rates)), 1);
        } else {
            return (string)0;
        }
    }
}
