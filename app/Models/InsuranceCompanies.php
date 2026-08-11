<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceCompanies extends Model
{
    use HasFactory;
    public $fillable = [
        'clinic_id','insurance_id', 'name_en', 'name_ar','phone','fax','email','website','code','provider_id','type', 'status',
        'insurance_company_id','claims_management_company', 'tax', 'policy_number','date_from','date_to'
    ];

    function clinic()
    {
        return $this->belongsTo(Clinic::class ,'clinic_id');
    }
}
