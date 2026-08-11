<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $id = request()->route('customer');

        $rules = [
            'name'                 => 'required|string',
            'address'              => 'required|string',
            'fax'                  => 'nullable|string',
            'website'              => 'nullable|string',
            'representative_name'  => 'nullable|string',
            'note'                 => 'nullable|string',
            'phone'                => 'required|string|unique:customers,phone',
            'email'                => 'nullable|email|unique:customers,email',
            'representative_phone' => 'nullable|string|unique:customers,representative_phone',
        ];

        if (isset($id)) {
            $rules["phone"]                = "required|string|unique:customers,phone," . $id;
            $rules["representative_phone"] = "required|string|unique:customers,representative_phone," . $id;
            $rules["email"]                = "required|string|email|unique:customers,email," . $id;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // required
            'name.required'    => trans('pharmacy.required'),
            'phone.required'   => trans('pharmacy.required'),
            'address.required' => trans('pharmacy.required'),

            // string
            'name.string'    => trans('pharmacy.string'),
            'address.string' => trans('pharmacy.string'),

            // email
            'email.email' => trans('pharmacy.email'),

            // numeric
            'phone.numeric'       => trans('pharmacy.numeric'),

        ];
    }
}
