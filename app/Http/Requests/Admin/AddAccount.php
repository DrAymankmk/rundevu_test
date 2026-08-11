<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AddAccount extends Request
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
            'email' => 'required',
            'name' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'image' => 'required',
            'password' => 'required|min:6',
            'country_id' => 'required',
            'city_id' => 'required',
            'phone1' => 'required',
        ];
    }

    function messages()
    {
        return [
            'name.required' => trans('messages.auth.name'),
            'email.required' => trans('messages.auth.email'),
            'address.required' => trans('messages.auth.address'),
            'lat.required' => trans('messages.auth.lat'),
            'lng.required' => trans('messages.auth.lng'),
            'image.required' => trans('messages.auth.image'),
            'password.required' => trans('messages.auth.password'),
            'country_id.required' => trans('messages.auth.country_id'),
            'city_id.required' => trans('messages.auth.city_id'),
            'phone1.required' => trans('admin.accounts.phone1'),

        ];
    }
}
