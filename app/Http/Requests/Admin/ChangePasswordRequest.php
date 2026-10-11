<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\Request;

class ChangePasswordRequest extends AdminRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password' => [
                'required',
                'string',
            ],

            'new_password' => [

                'required',

                'string',

                'min:6',

                'max:255',

            ],

            'confirm_password' => 'required|same:new_password|min:6'
        ];
    }

    function messages()
    {
        return [
            'new_password.required' => trans('validation.new_password'),
            'new_password.min' => trans('validation.min.numeric'),

            'confirm_password.required' => trans('validation.confirm_password'),
            'confirm_password.min' => trans('validation.min.numeric'),

        ];
    }
}
