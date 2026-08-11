<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResultDetails extends Model
{
    use HasFactory;
    protected $table = 'test_results_details';
    public function getImagesAttribute($value)
    {
        if ($value) {
            return asset('media/test_result/' . $value);
        } else {
            return asset('media/test_result/user.png');
        }
    }

    public function setImagesAttribute($value)
    {
        if ($value) {
            $img_name = uniqid() . $value->getClientOriginalName() . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/test_result/'), $img_name);
            $this->attributes['images'] = $img_name;
        }
    }
}
