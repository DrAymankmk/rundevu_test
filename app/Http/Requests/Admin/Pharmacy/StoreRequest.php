<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name'                  => 'required|string',
            'phone'                 => 'nullable|string',
            'address'               => 'nullable|string',
            'responsible_person_id' => 'required|numeric|exists:clinics,id',
        ];
    }

    public function messages()
    {
        return [
            // required
            'name.required'                  => trans('pharmacy.required'),
            'phone.required'                 => trans('pharmacy.required'),
            'address.required'               => trans('pharmacy.required'),
            'responsible_person_id.required' => trans('pharmacy.required'),

            // string
            'name.string'    => trans('pharmacy.string'),
            'address.string' => trans('pharmacy.string'),



            // numeric
            'phone.numeric'       => trans('pharmacy.numeric'),

        ];
    }

}
