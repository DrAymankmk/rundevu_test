<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllPermissions extends Model
{
    use HasFactory;


    function child()
    {
        return $this->hasMany(AllPermissions::class, 'parent_id');

    }

    public static function parent($admin_id, $permissions_id, $lang)
    {
        $query = AllPermissions::where('parent_id', $permissions_id)->where('status', 1)->select('id', 'permission', 'name_' . $lang . ' as name', 'flag')->get();
        $per_list = [];
        foreach ($query as $per) {
            $per_item['id'] = $per->id;
            $per_item['name'] = $per->name;
            $per_item['flag'] = (int)$per->flag;
            $per_item['permission'] = $per->permission;
            if ($admin_id) {
                $per_item['is_selected'] = ClinicsPermissions::where('admin_id',$admin_id)->where('child_id',$per->id)->exists();
            } else {
                $per_item['is_selected'] = false;
            }
            $per_list[] = $per_item;
        }
        return $per_list;

    }


}
