<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionsPackageClinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'package_id',
        'start_date',
        'end_date',
        'status',
    ];
}
