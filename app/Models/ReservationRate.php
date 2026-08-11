<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationRate extends Model
{
    use HasFactory;

    public $fillable = ['clinic_id','doctor_id','reservation_id', 'user_id', 'comment','rate_value','status'];

    function users () {
        return $this->belongsTo(User::class,'user_id');
    }


    function clinics () {
        return $this->belongsTo(Clinic::class,'clinic_id');
    }

    function doctors () {
        return $this->belongsTo(Clinic::class,'doctor_id');
    }

    function reservations () {
        return $this->belongsTo(Reservations::class,'reservation_id');
    }

}
