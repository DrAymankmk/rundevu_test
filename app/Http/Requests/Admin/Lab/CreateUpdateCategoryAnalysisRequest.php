<?php

namespace App\Http\Requests\Admin\Lab;

use App\Http\Requests\AdminRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateCategoryAnalysisRequest extends AdminRequest
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
            'price' => 'required',
            'category_id' => 'required',
        ];
    }

    function messages()
    {
        return [
            'name_ar.required' => trans('admin.name_ar'),
            'name_en.required' => trans('admin.name_en')
            ];
    }
}
