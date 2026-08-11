<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsType extends Model
{
    use HasFactory;

    public $fillable = [
        'name_ar','name_en', 'status'
    ];

    function permission_type()
    {
        return $this->hasMany(PermissionsRequests::class,'permission_type');

    }
}
