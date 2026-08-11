<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicServices extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id', 'service_id', 'created_by', 'price', 'type', 'status'];

    function services()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }


}
