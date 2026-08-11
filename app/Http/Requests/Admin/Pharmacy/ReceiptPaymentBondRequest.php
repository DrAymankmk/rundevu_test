<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class ReceiptPaymentBondRequest extends FormRequest
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
            'account_id'     => 'nullable',
            'movement_type'  => 'required|string|in:receipt_bond,exchange_bond',
            'price'          => 'required|numeric',
            'payment_method' => 'required|string|in:cache,visa',
            'date'           => 'required', //|date|date_format:Y-m-d
        ];
    }

    public function messages()
    {
        return [
            // required
            'account_id.required'     => trans('pharmacy.required'),
            'movement_type.required'  => trans('pharmacy.required'),
            'price.required'          => trans('pharmacy.required'),
            'payment_method.required' => trans('pharmacy.required'),
            'date.required'           => trans('pharmacy.required'),




            // numeric
            'price.numeric'       => trans('pharmacy.numeric'),

        ];
    }
}
