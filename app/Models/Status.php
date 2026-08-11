<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    function reservations()
    {
        return $this->hasMany(Reservations::class,'status_id');
    }
}
