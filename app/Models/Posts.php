<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id', 'image', 'content'];

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/posts/' . $value);
        } else {
            return asset('media/user.png');
        }
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/posts/'), $img_name);
            $this->attributes['image'] = $img_name;
        }
    }


    function clinic () {
        return $this->belongsTo(Clinic::class,'clinic_id');
    }
}
