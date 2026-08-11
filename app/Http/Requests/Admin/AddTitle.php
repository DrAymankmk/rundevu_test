<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class AddTitle extends AdminRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title_ar' => 'required',
            'title_en' => 'required',
        ];
    }

    function messages()
    {
        return [
            'title_ar.required' => trans('admin.name_ar'),
            'title_en.required' => trans('admin.name_en'),
        ];
    }
}
