<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public $fillable = ['name_en', 'name_ar','specialty_id','category_id','price','notes','abbrev','unit','normal_value', 'type','status'];

    function category()
    {
        return $this->belongsTo(ServicesCategory::class,'category_id');
    }

    function specialties()
    {
        return $this->belongsTo(Specialty::class,'specialty_id');
    }

    function attributes()
    {
        return $this->hasMany(ServiceAnalysisAttributes::class,'service_id')->where('parent_id',null)->orderBy('age_id','asc');
    }
    function attributes_parent()
    {
        return $this->hasMany(ServiceAnalysisAttributes::class,'parent_id');
    }

    function clinic_service()
    {
        return $this->hasOne(ClinicServices::class,'service_id');
    }
}
