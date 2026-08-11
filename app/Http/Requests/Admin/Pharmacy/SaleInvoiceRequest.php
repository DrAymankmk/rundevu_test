<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class SaleInvoiceRequest extends FormRequest
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
        $id = request()->route('sale_invoice');

        $rules = [
            'is_saved_invoice'        => 'required|numeric|in:0,1',
            'invoice_type'            => 'required|string|in:purchase,sales,patient_sales',
            'invoice_number'          => 'required|numeric',
            'movement_type'           => 'required|string|in:sales,sales_return',
            'store_id'                => 'required|numeric',
            'customer_id'             => 'required|exists:customers,id',
            'cache'                   => 'nullable|numeric',
            'account_tree_id'         => 'nullable|numeric',
            'pharmacy_account_value'  => 'nullable|numeric',
            'transfer'                => 'nullable|numeric',
            'okay_or_cache'           => 'required|in:okay,cache',
            'date'                    => 'required',
            'note'                    => 'nullable|string',
            'drug_id'                 => 'required|array|min:1',
            'drug_guidelines'         => 'nullable|array|min:1',
            'balance'                 => 'nullable|array|min:1',
            'price'                   => 'required|array|min:1',
            'quantity'                => 'required|array|min:1',
            'unit_type'               => 'required|array|min:1',
            'percentage_discount'     => 'nullable|array|min:1',
            'value_discount'          => 'nullable|array|min:1',
            'tax'                     => 'required|array|min:1',
            'total_amount'            => 'required|array|min:1',
        ];

        if (isset($id)) {
            $rules['pharmacy_invoice_item_id'] = 'nullable|array|min:1';
            $rules['pharmacy_invoice_id']      = 'required';
        }

        return $rules;
    }
    public function messages()
    {
        return [
            // required

            'invoice_number.required'  => trans('pharmacy.required'),
            'note.required'            => trans('pharmacy.required'),
            'customer_id.required'     => trans('pharmacy.required'),
            'okay_or_cache.required'   => trans('pharmacy.required'),
            'date.required'            => trans('pharmacy.required'),
            'drug_id.required'         => trans('pharmacy.required'),
            'drug_guidelines.required' => trans('pharmacy.required'),
            'balance.required'         => trans('pharmacy.required'),
            'price.required'           => trans('pharmacy.required'),
            'quantity.required'        => trans('pharmacy.required'),
            'unit_type.required'       => trans('pharmacy.required'),
            'invoice_type.required'    => trans('pharmacy.required'),
            'tax.required'             => trans('pharmacy.required'),




            // numeric
            'cache.numeric'                  => trans('pharmacy.numeric'),
            'account_tree_id.numeric'        => trans('pharmacy.numeric'),
            'pharmacy_account_value.numeric' => trans('pharmacy.numeric'),
            'transfer.numeric'               => trans('pharmacy.numeric'),

        ];
    }


}
