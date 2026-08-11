<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicOffer extends Model
{
    use HasFactory,SoftDeletes;
    public $fillable = ['clinic_id','specialty_id', 'title_ar', 'title_en','discount','start_date','end_date','status'];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }
}
