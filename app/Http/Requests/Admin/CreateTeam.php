<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CreateTeam extends Request
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
            'name' => 'required',
            'job_title_ar' => 'required',
            'job_title_en' => 'required',
            'image' => 'required',
        ];
    }

    function messages()
    {
        return [
            'name.required' => trans('admin.name'),
            'job_title_ar.required' => trans('admin.job_title_ar'),
            'job_title_en.required' => trans('admin.job_title_en'),
            'image.required' => trans('admin.image'),

        ];
    }
}
