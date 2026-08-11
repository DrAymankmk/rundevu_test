<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPointRule extends Model
{
    protected $casts = [
        'status' => 'boolean',
    ];

    protected $fillable = [
        'key',
        'name_ar',
        'name_en',
        'points',
        'max_per_day',
        'min_words',
        'expires_after_months',
        'status',
    ];
}
