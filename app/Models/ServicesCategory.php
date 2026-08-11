<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesCategory extends Model
{
    use HasFactory;

    public $fillable = ['name_en', 'name_ar','type','clinic_id','flag','status'];

    function analysis () {
        return $this->hasMany(Service::class,'category_id')->where('type',1);
    }

    function rays () {
        return $this->hasMany(Service::class,'category_id')->where('type',2);
    }

}
