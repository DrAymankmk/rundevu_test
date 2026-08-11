<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorCondition extends Model
{
    use HasFactory;
    public $fillable = ['doctor_id', 'appointments_online', 'appointments_reception','number_patients', 'condition','consultation_duration', 'status'];

}
