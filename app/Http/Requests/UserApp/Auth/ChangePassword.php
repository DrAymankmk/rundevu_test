<?php

namespace App\Http\Requests\UserApp\Auth;

use App\Http\Requests\Request;

class ChangePassword extends Request
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
            'new_password' => 'required|min:6',
        ];
    }

    function messages()
    {
        return [
            'new_password.required' => trans('validation.new_password'),
            'new_password.min' => trans('validation.min.numeric'),
        ];
    }
}
