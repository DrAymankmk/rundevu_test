<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable = [
        'name_ar','name_en', 'parent_id','status','created_by'
    ];

    public function sub_specialties()
    {
        return $this->hasMany(Specialty::class,'parent_id')->select('id','name_en as name');
    }

    public function admin()
    {
        return $this->belongsTo(Clinic::class,'created_by');
    }

    public function sub_specialties_list()
    {
        return $this->hasMany(Specialty::class,'parent_id');
    }


    public function clinic_specialties()
    {
        return $this->hasMany(ClinicSpecialist::class,'clinic_id');
    }
}
