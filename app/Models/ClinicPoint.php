<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicPoint extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id', 'content_ar', 'content_en', 'status','point'];

    function account()
    {
        return $this->belongsTo(Clinic::class,'clinic_id');

    }
}
