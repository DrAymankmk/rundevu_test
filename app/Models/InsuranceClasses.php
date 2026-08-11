<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceClasses extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id','insurance_id', 'name_en', 'name_ar', 'status'];

}
