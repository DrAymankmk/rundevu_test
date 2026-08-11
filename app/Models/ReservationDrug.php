<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationDrug extends Model
{
    use HasFactory;

    public $fillable = ['reservation_id', 'user_id', 'doctor_id','drug_id','repetition','nums_days','notes','status'];

    function drugs () {
        return $this->belongsTo(Drugs::class,'drug_id');
    }

    function users () {
        return $this->belongsTo(User::class,'user_id');
    }

    function doctor () {
        return $this->belongsTo(Clinic::class,'doctor_id');
    }

    function reservation () {
        return $this->belongsTo(Reservations::class,'reservation_id');
    }

}
