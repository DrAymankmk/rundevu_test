<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftEmployee extends Model
{
    use HasFactory;
    public $fillable = ['account_type', 'clinic_id', 'employee_id', 'shift_id','day_id', 'dateA', 'attendance_status', 'check_in', 'check_out', 'checkin_another_employee', 'checkout_another_employee', 'status'];

    function shift () {
        return $this->belongsTo(Shift::class,'shift_id');
    }

    function employee () {
        return $this->belongsTo(Clinic::class,'employee_id');
    }


    function audience_another_employee () {
        return $this->belongsTo(Clinic::class,'checkin_another_employee');
    }

    function leave_another_employee () {
        return $this->belongsTo(Clinic::class,'checkout_another_employee');
    }

    function days()
    {
        return $this->belongsTo(Day::class,'day_id');

    }




}
