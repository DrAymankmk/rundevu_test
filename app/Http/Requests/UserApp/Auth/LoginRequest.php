<?php

namespace App\Http\Requests\UserApp\Auth;

use App\Http\Requests\Request;

class LoginRequest extends Request
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'nullable|string',
            'email' => 'required_without:login|string',
            'password'=>'required',
        ];

    }

    function messages()
    {
        return [
            'email.required' => trans('messages.email'),
            'email.required_without' => trans('messages.email'),
            'password.required' => trans('messages.password'),

        ];
    }


}
