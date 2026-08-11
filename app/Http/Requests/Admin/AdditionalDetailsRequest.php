<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class AdditionalDetailsRequest extends AdminRequest
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
            'additions_id' => 'required',
        ];
    }

    function messages()
    {
        return [
            'name_ar.required' => trans('admin.name_ar'),
            'name_en.required' => trans('admin.name_en'),
            'additions_id.required' => 'اختر اسم الاضافة',
        ];
    }
}
