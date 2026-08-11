<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseInvoiceRequest extends FormRequest
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
        $id = request()->route('purchase_invoice');

        $rules =  [
            'is_saved_invoice'    => 'required|in:0,1',
            'invoice_number'      => 'required|numeric',
            'invoice_type'        => 'required|string|in:purchases,sales,patient_sales',
            'movement_type'       => 'required|string|in:purchases,purchases_return',
            'payment_method'      => 'required|string|in:cache,visa',
            'supplier_id'         => 'required|numeric',
            'paid_value'          => 'required|numeric',
            'store_id'            => 'required|numeric',
            'barcode'             => 'required|array|min:1|in:barcode,without_barcode',
            'drug_id'             => 'required|array|min:1',
            'price'               => 'required|array|min:1',
            'quantity'            => 'required|array|min:1',
            'unit_type'           => 'required|array|min:1|in:grand_unit,micro_unit',
            'bonus'               => 'nullable|array|min:1',
            'percentage_discount' => 'nullable|array|min:1',
            'value_discount'      => 'nullable|array|min:1',
            'fixed_discount'      => 'nullable|array|min:1',
            'tax'                 => 'nullable|array|min:1',
            'today_date'          => 'required|array|min:1',
            'production_date'     => 'required|array|min:1',
            'expired_date'        => 'required|array|min:1',

        ];

        if (isset($id)) {
            $rules['pharmacy_invoice_item_id'] = 'nullable|array|min:1';
            $rules['pharmacy_invoice_id']      = 'required';

        }
        return $rules;
    }
}
