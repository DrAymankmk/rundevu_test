<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id', 'attendance_period', 'leaving_period','extra_time'];
}
