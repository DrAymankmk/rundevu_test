<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSigns extends Model
{
    use HasFactory;
    protected $fillable = ['heat','pulse','blood_pressure' ,'pain_rate' ,'weight','height','breathing',
    'oxygen_ratio','body_mass_rate' ,'FBS' ,'RBS' ,'clinic_id' ,'emergency_id','reservation_id','user_id','doctor_id','notes'];

    public function doctor () {

        return $this->belongsTo(Clinic::class,'doctor_id');
    }
    public function user () {

        return $this->belongsTo(User::class,'user_id');
    }
}
