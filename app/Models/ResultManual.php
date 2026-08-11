<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultManual extends Model
{
    use HasFactory;

    protected $table = 'result_manuals';

    protected $fillable = [
        'patient_service_id', 'PLT', 'RBCs', 'HB', 'HCT', 'MCV',
        'MCH', 'MCHC', 'RDW', 'WBCs', 'comment',
    ];

    public function patientService()
    {
        return $this->belongsTo(PatientService::class, 'patient_service_id');
    }
}
