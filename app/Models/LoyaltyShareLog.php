<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyShareLog extends Model
{
    protected $fillable = [
        'user_id',
        'clinic_id',
        'shareable_type',
        'shareable_id',
    ];
}
