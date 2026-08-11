<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicsPermissions extends Model
{
    use HasFactory;
    public $fillable = ['admin_id', 'parent_id', 'child_id'];

    public function permissions() {
        return $this->belongsTo(AllPermissions::class,'child_id');
    }

    function supervisor()
    {
        return $this->belongsTo(Clinic::class, 'admin_id');
    }



}
