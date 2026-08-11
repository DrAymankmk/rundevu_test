<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmergencyHospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'phone',
        'image',
        'city_id',
        'region_id',
        'address',
        'lat',
        'lng',
        'status',
    ];

    public function getImageAttribute($value)
    {
        return $value ? asset('media/emergency_hospitals/' . $value) : asset('media/logo/logo.png');
    }

    public function setImageAttribute($value)
    {
        if ($value && is_object($value) && method_exists($value, 'getClientOriginalExtension')) {
            $imgName = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/emergency_hospitals/'), $imgName);
            $this->attributes['image'] = $imgName;
        }
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function region()
    {
        return $this->belongsTo(Regions::class, 'region_id');
    }

    public static function nearby($lat, $lng, $radius = 50)
    {
        return static::select(DB::raw('emergency_hospitals.*, ( 6371 * acos( cos( radians(' . (float) $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . (float) $lng . ') ) + sin( radians(' . (float) $lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->where('status', 1)
            ->having('distance', '<', $radius)
            ->orderBy('distance');
    }
}
