<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    public $fillable = [
        'name_en',
        'name_ar',
        'features_en',
        'features_ar',
        'duration',
        'price',
        'discount',
        'price_after_discount',
        'free_months',
        'status',
    ];

    function subscriptions_package_users()
    {
        return $this->hasMany(SubscriptionsPackageUser::class, 'package_id');
    }


    public function subscriptions()
    {
        return $this->hasMany(SubscriptionsPackageClinic::class);
    }

}
