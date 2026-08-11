<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationVitalSigns extends Model
{
    use HasFactory;

    public $fillable = ['reservation_id','heat', 'weight','pulse', 'height', 'breathing', 'pregnant','blood_pressure','sports_habits'];

}
