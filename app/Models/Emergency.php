<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id','user_id', 'nurse_id', 'notes', 'enter_date', 'exit_date','type'];
   
    public function user () {

        return $this->belongsTo(User::class,'user_id');
    }

    public function nurse () {

        return $this->belongsTo(Clinic::class, 'nurse_id', 'id');
    }
    public function doctors()
    {
        return $this->belongsToMany(Clinic::class, 'doctor_emergencies', 'emergency_id', 'doctor_id');
    }
    public function nurses()
    {
        return $this->belongsToMany(Clinic::class, 'nurse_emergencies', 'emergency_id', 'nurse_id');
    }
    public function drugs()
    {
        return $this->belongsToMany(Drugs::class, 'drugs_emergencies', 'emergency_id', 'drugs_id');
    }
    public function vitalSigns()
    {
        return $this->hasMany(VitalSigns::class);
    }
}
