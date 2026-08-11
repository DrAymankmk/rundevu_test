<?php

namespace App\Http\Requests\Clinics;

use App\Http\Requests\Request;

class CreateBranchRequest extends Request
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
            'name' => ['required', 'string', 'max:250'],
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
            'image' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:30000'],

        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('messages.auth.name'),
            'phone.required' => trans('messages.phone'),
            'email.required' => trans('messages.email'),
            'password.required' => trans('messages.password'),
            'image.required' => trans('messages.auth.image'),
        ];
    }


}
