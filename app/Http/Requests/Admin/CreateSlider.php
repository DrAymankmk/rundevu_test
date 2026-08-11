<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class CreateSlider extends AdminRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'images' => 'required',
        ];
    }

    function messages()
    {
        return [
            'images.required' => trans('admin.images'),
        ];
    }
}
