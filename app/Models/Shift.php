<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id', 'account_type','name', 'time_from','time_to', 'status','minute_allow_delay'];

}
