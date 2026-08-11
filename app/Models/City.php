<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $fillable = [
        'name_ar','name_en', 'country_id','status','created_by'
    ];

    public function admin()
    {
        return $this->belongsTo(Clinic::class,'created_by');
    }

    public function clinics()
    {
        return $this->hasMany(Clinic::class,'city_id');
    }
}
