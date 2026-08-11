<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class AddDepartment extends AdminRequest
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
        ];
    }

    function messages()
    {
        return [
            'name_ar.required' => trans('admin.name_ar'),
            'name_en.required' => trans('admin.name_en'),
        ];
    }
}
