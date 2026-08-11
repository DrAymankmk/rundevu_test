<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class ClinicRequest extends FormRequest
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
        $id = request()->route('pharmacy');

        $rules = [
            'name'             => "required|string",
            'address'          => "required|string",
            'phone'            => "required|numeric",
            'post_number'      => "nullable|numeric",
            'fax'              => "nullable|string",
            'email'            => "required|string|email|unique:clinics,email",
            'website'          => "nullable|string",
            'monitor_username' => "nullable|string",
            'monitor_password' => "nullable|string",
        ];

        if(isset($id)){
            $rules["email"] = "required|string|email|unique:clinics,email,". $id;
        }
        return $rules;
    }

    function messages()
    {
        return [
            // required
            'name.required'    => trans('pharmacy.required'),
            'address.required' => trans('pharmacy.required'),
            'phone.required'   => trans('pharmacy.required'),
            'email.required'   => trans('pharmacy.required'),

            // string
            'name.string'             => trans('pharmacy.string'),
            'address.string'          => trans('pharmacy.string'),
            'fax.string'              => trans('pharmacy.string'),
            'email.string'            => trans('pharmacy.string'),
            'website.string'          => trans('pharmacy.string'),
            'monitor_username.string' => trans('pharmacy.string'),
            'monitor_password.string' => trans('pharmacy.string'),

            // numeric
            'phone.numeric'       => trans('pharmacy.numeric'),
            'post_number.numeric' => trans('pharmacy.numeric'),

            // email
            'email.email'  => trans('pharmacy.email_valid'),
            'email.unique' => trans('pharmacy.unique'),


        ];
    }
}
