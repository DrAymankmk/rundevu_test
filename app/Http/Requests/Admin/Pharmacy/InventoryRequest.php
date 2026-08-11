<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
            'store_id' => 'required|exists:stores,id',
            'drug_id'  => 'required|exists:drugs,id',
            'quantity' => 'required|numeric',
            'date'     => 'required|date|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            // required
            'store_id.required' => trans('pharmacy.required'),
            'drug_id.required'  => trans('pharmacy.required'),
            'quantity.required' => trans('pharmacy.required'),
            'date.required'     => trans('pharmacy.required'),




            // numeric
            'quantity.numeric'       => trans('pharmacy.numeric'),

        ];
    }

}
