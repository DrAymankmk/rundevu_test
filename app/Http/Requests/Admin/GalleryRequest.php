<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class GalleryRequest extends Request
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
            'image' => 'required',
        ];
    }

    function messages()
    {
        return [
            'image.required' => trans('messages.image'),

        ];
    }
}
