<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CreateBlogsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'image' => 'required',
        ];
    }

    function messages()
    {
        return [
            'title_ar.required' => trans('admin.title_ar'),
            'title_en.required' => trans('admin.title_en'),
            'description_ar.required' => trans('admin.description_ar'),
            'description_en.required' => trans('admin.description_en'),
            'image.required' => trans('admin.image'),

        ];
    }
}
