<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusConversion extends Model
{
    use HasFactory;
    public $fillable = ['user_id', 'doctor_id','notes','image','type','reservation_id','reception_id'];

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/status_conversion/' . $value);
        } else {
            return asset('media/clinics/user.png');
        }
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/status_conversion/'), $img_name);
            $this->attributes['image'] = $img_name;
        }
    }

    function reservation()
    {
        return $this->belongsTo(Reservations::class,'reservation_id');
    }

    function patient()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
