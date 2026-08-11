<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAnalysisAttributes extends Model
{
    use HasFactory;
    public $fillable = ['name', 'service_id','age_id','parent_id','unit','normal_value','normal_value_female','status'];

}
