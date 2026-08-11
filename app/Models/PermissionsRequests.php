<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsRequests extends Model
{
    use HasFactory;
    public $fillable = ['dateA', 'permission_owner', 'clinic_id','permission_type','reason','image','status'];


    function permissions_type () {
        return $this->belongsTo(PermissionsType::class,'permission_type');
    }

    function account () {
        return $this->belongsTo(Clinic::class,'permission_owner');
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/permissions/' . $value);
        }
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/permissions/'), $img_name);
            $this->attributes['image'] = $img_name;
        }
    }
}
