<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    function child()
    {
        return $this->hasMany(Permission::class, 'parent_id')->where('status', 1)->orderBy('id');

    }

    public function getDisplayNameAttribute()
    {
        $locale = app()->getLocale();

        return $locale == 'ar'
            ? ($this->name_ar ?: $this->permission)
            : ($this->name_en ?: $this->permission);
    }
}
