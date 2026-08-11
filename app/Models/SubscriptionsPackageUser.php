<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionsPackageUser extends Model
{
    use HasFactory;

    public $fillable = ['package_id', 'user_id', 'price', 'invoice_number','info_payment', 'expired_date', 'status'];
}
