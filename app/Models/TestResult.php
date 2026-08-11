<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    function clinic () {
        return $this->belongsTo(Clinic::class,'clinic_id');
    }

    function report_details () {
        return $this->hasMany(TestResultDetails::class,'test_result_id')->select('id','images');
    }

    function user () {
        return $this->belongsTo(User::class,'user_id');
    }

    function lab () {
        return $this->belongsTo(Clinic::class,'doctor_id');
    }
}
