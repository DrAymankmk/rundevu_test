<?php

namespace App\Http\Requests\UserApp\Auth;
use App\Http\Requests\Request;

class RegisterRequest extends Request
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
            'dob'  => ['required', 'date_format:Y-m-d'],
            'gender' => ['required', 'in:1,2'],
            'phone' => 'required|numeric',
            'ID_Number' => 'required|numeric',
            'email' => 'required|email',
            'city_id'  => ['required', 'exists:cities,id'],
            'password' => 'required|min:6',
            'referral_code' => 'nullable',
            'lat'      => ['required', 'string', 'max:200'],
            'lng'    => ['required', 'string', 'max:200'],
            'address'   => ['required', 'string', 'max:250'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:30000'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('messages.auth.name'),
            'gender.required' => trans('messages.employees.gender'),
            'dob.required' => trans('messages.auth.date_created'),
            'phone.required' => trans('messages.phone'),
            'ID_Number.required' => trans('messages.ID_Number'),
            'email.required' => trans('messages.email'),
            'password.required' => trans('messages.password'),
            'referral_code.required' => trans('messages.auth.communication_officer'),
            'city_id.required' => trans('messages.auth.city_id'),
            'address.required' => trans('messages.auth.address'),
            'lat.required' => trans('messages.auth.lat'),
            'lng.required' => trans('messages.auth.lng'),
            'image.required' => trans('messages.auth.image'),
            'qr_code.required' => trans('messages.auth.qr_code'),
        ];
    }


}
