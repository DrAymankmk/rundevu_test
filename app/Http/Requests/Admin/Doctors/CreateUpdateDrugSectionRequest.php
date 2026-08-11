<?php

namespace App\Http\Requests\Admin\Doctors;

use App\Http\Requests\AdminRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateDrugSectionRequest extends AdminRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'required',
            'name_en' => 'required',
            'medicine_type' => 'required',
            'concentration_ratio' => 'required',
            'concentration_type' => 'required',
        ];
    }

    function messages()
    {
        return [
            'name_ar.required' => trans('admin.name_ar'),
            'name_en.required' => trans('admin.name_en'),
            'medicine_type.required' => trans('admin.doctor.medicine_type'),
            'concentration_ratio.required' => trans('admin.doctor.concentration_ratio'),
            'concentration_type.required' => trans('admin.doctor.concentration_type'),
        ];
    }
}
