<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class MedicineDepartmentRequest extends FormRequest
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
        $rules = [
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'tax'     => 'required|numeric',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            // required
            'name_ar.required' => trans('pharmacy.required'),
            'name_en.required' => trans('pharmacy.required'),
            'tax.required'     => trans('pharmacy.required'),

            // string
            'name_ar.string'             => trans('pharmacy.string'),
            'name_en.string'             => trans('pharmacy.string'),

            // numeric
            'tax.numeric'       => trans('pharmacy.numeric'),

        ];
    }

}
