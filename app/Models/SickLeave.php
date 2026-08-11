<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SickLeave extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id', 'reservation_id', 'works', 'Workplace','sick_days', 'from_date',
        'to_date', 'medical_company', 'impossible_treat', 'physician_leave','Diagnosis', 'notes',
        'directed_to', 'letter_no', 'letter_date', 'companion_name','relation_patient', 'occupation',
        'Workplaces', 'type','companion_sick_days','companion_from_date','companion_to_date'
    ];

    function reservation()
    {
        return $this->belongsTo(Reservations::class,'reservation_id');
    }

    function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
