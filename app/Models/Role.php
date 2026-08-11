<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles;

class Role extends SpatieRole
{
    use HasFactory,HasRoles;

    protected $fillable = ['name','name_ar', 'status'];

    // Relationship to get child roles
//    public function permissions()
//    {
//        return $this->belongsToMany(
//            Permission::class,  // Related Model
//            'role_has_permissions', // Table name
//            'role_id',  // Foreign key on pivot table for Role
//            'permission_id' // Foreign key on pivot table for Permission
//        );
//    }
}
