<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicSpecialist extends Model
{
    use HasFactory;
    public $fillable = ['clinic_id', 'specialty_id','type','status','deleted_at'];

    public function specialties()
    {
        return $this->belongsTo(Specialty::class,'specialty_id');
    }

    public function owner()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id')->where(function ($query) {
            $query->where('parent_id', auth()->user()->parent_id)
                ->where(['app_type'=>3,'status'=>1]);
        });
    }

    public static function clinic_specialties($employee_id,$specialty_id,$lang)
    {
        $query = Specialty::where('parent_id',$specialty_id)->where('status', 1)->select('id', 'name_' . $lang . ' as name')->get();
        $specialists_list = [];
        foreach ($query as $specialist) {
            $per_item['id'] = $specialist->id;
            $per_item['name'] = !empty($specialist->name) ? $specialist->name : "";
            if ($employee_id) {
                $per_item['is_selected'] = ClinicSpecialist::where('clinic_id',$employee_id)->where('specialty_id',$specialist->id)->exists();
            } else {
                $per_item['is_selected'] = true;
            }
            $specialists_list[] = $per_item;
        }
        return $specialists_list;
    }

}
