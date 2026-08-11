<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['title_en','title_ar','content_en','content_ar','settings_type','app_type','image'];


    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/settings/' . $value);
        } else {
            return asset('media/settings/logo.png');
        }
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/settings/'), $img_name);
            $this->attributes['image'] = $img_name;
        }
    }

    function type()
    {
        return $this->belongsTo(AppType::class,'app_type');

    }
}
