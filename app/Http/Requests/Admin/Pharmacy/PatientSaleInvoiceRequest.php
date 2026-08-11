<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class PatientSaleInvoiceRequest extends FormRequest
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
        $id = request()->route('patient_sale_invoice');

        $rules = [
            'is_saved_invoice'       => 'required|numeric|in:0,1',
            'invoice_number'         => 'required|numeric',
            'invoice_type'           => 'required|string|in:purchase,sales,patient_sales',
            'movement_type'           => 'required|string|in:sales,sales_return',
            'store_id'                => 'required|numeric',
            'patient_id'             => 'required|exists:users,id',
            'doctor_id'              => 'required',
            'cache'                  => 'nullable|numeric',
            'account_tree_id'        => 'nullable|numeric',
            'pharmacy_account_value' => 'nullable|numeric',
            'transfer'               => 'nullable|numeric',
            'okay_or_cache'          => 'required|in:okay,cache',
            'date'                   => 'required',
            'note'                   => 'nullable|string',
            'drug_id'                => 'required|array|min:1',
            'balance'                => 'nullable|array|min:1',
            'price'                  => 'required|array|min:1',
            'quantity'               => 'required|array|min:1',
            'unit_type'              => 'required|array|min:1',
            'percentage_discount'    => 'nullable|array|min:1',
            'value_discount'         => 'nullable|array|min:1',
            'tax'                    => 'required|array|min:1',
            'total_amount'           => 'required|array|min:1',
        ];

        if (isset($id)) {
            $rules['pharmacy_invoice_item_id'] = 'nullable';
            $rules['pharmacy_invoice_id']      = 'required';

        }

        return $rules;
    }

}
