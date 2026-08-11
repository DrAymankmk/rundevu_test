<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPoints extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id','user_id', 'content_ar', 'content_en', 'status','point'];
}
