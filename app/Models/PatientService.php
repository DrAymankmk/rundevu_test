<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientService extends Model
{
    use HasFactory;
    protected $fillable = [
       'invoice_id', 'service_id', 'user_id', 'doctor_id', 'price','reservation_id',
        'debit', 'type','status','lab_result','notes','lab_notes','discount','qty','tax','nurse_id','clinic_id','point_id',
        'confirm','confirm_insurance',
    ];

    public function getLabResultAttribute($value)
    {
        if ($value) {
            return asset('media/lab_result/' . $value);
        }
    }

    public function setLabResultAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/lab_result/'), $img_name);
            $this->attributes['lab_result'] = $img_name;
        }
    }


    public function services () {

        return $this->belongsTo(Service::class,'service_id')->where('status',1);
    }

    public function reservations () {

        return $this->belongsTo(Reservations::class,'reservation_id');
    }
    public function user () {
        return $this->belongsTo(User::class,'user_id');
    }

    public function result_manual () {

        return $this->hasone(ResultManual::class,'patient_service_id');
    }

    public function doctor () {
        return $this->belongsTo(Clinic::class,'doctor_id');
    }
    public function nurse () {

        return $this->belongsTo(Clinic::class, 'nurse_id', 'id');
    }

    public function clinic () {

        return $this->belongsTo(Clinic::class,'clinic_id');
    }


    static function lab_result($service,$user_id,$lang)
    {

        $categories_list = [];
        $category_item = [];
        $service_analysis_attributes = ServiceAnalysisAttributes::where(['service_id'=>$service->service_id,'parent_id'=>null])->get();
        foreach ($service_analysis_attributes as $parent) {
            $category_item['id'] = $parent->id;
            $category_item['test_name'] = $parent->name;
            $subCategories = ServiceAnalysisAttributes::where(['service_id'=>$service->service_id,'parent_id'=>$parent->id])->get();
            $category_item['subService'] = array();
            foreach ($subCategories as $subCategory) {
                $val['id'] = $subCategory->id;
                $val['test_name'] =  $parent->name ?? "";
                $val['unit'] =  $parent->unit ?? "";
                $val['normal_value'] =  $parent->normal_value ?? "";
                $val['result'] =  "";
                array_push($category_item['subService'], $val);
            }
            $categories_list[] = $category_item;
        }
        return $categories_list;
    }
}
