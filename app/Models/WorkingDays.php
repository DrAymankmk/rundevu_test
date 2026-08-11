<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingDays extends Model
{
    use HasFactory;
    public $fillable = ['pharmacist_id', 'day_id','check_in_date','check_out_date', 'status','deleted_at'];

}
