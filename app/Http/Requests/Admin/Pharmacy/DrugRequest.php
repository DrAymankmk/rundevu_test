<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class DrugRequest extends FormRequest
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
        $id = request()->route('drug');


        $rules = [
            'trade_name_ar'         => 'required|string',
            'trade_name_en'         => 'required|string',
            'scientific_name_ar'    => 'required|string',
            'scientific_name_en'    => 'required|string',
            'short_code'            => 'required|string|unique:drugs,short_code',
            'group_id'              => 'required|numeric|exists:drugs,id',
            'grand_unit_id'         => 'nullable|numeric|exists:item_units,id',
            'micro_unit_id'         => 'nullable|numeric|exists:item_units,id',
            'content_of_grand_unit' => 'nullable|numeric',
            'price_of_grand_unit'   => 'nullable|numeric',
            'discount'              => 'nullable|numeric',
            'supplier_id'           => 'nullable|numeric|exists:suppliers,id',
            'expiration_date'       => 'nullable',
            'returns'               => 'nullable|in:1,0',
            'limit_of_deficiencies' => 'nullable|numeric',
            'recession_limit'       => 'nullable|numeric',
            'concentration'         => 'nullable|string',
            'usage_or_form'         => 'nullable|string',
            'note'                  => 'nullable|string',
            'international_barcode' => 'nullable|string',
            'alternative_id'        => 'nullable|numeric',

        ];

        if (isset($id)) {
            $rules["short_code"]      = "required|string|unique:drugs,short_code," . $id;
            $rules["drug_section_id"] = "nullable|numeric|exists:drug_sections,id";
            $rules["expiration_date"] = "nullable|date";
        }

        return $rules;
    }

    function messages()
    {
        return [
            // required
            'trade_name_ar.required'      => trans('pharmacy.required'),
            'trade_name_en.required'      => trans('pharmacy.required'),
            'scientific_name_ar.required' => trans('pharmacy.required'),
            'scientific_name_en.required' => trans('pharmacy.required'),
            'short_code.required'         => trans('pharmacy.required'),
            'group_id.required'           => trans('pharmacy.required'),

            // string
            'trade_name_ar.string'         => trans('pharmacy.string'),
            'trade_name_en.string'         => trans('pharmacy.string'),
            'scientific_name_ar.string'    => trans('pharmacy.string'),
            'scientific_name_en.string'    => trans('pharmacy.string'),
            'short_code.string'            => trans('pharmacy.string'),
            'concentration.string'         => trans('pharmacy.string'),
            'usage_or_form.string'         => trans('pharmacy.string'),
            'note.string'                  => trans('pharmacy.string'),
            'international_barcode.string' => trans('pharmacy.string'),

            // numeric
            'group_id.numeric'              => trans('pharmacy.numeric'),
            'grand_unit_id.numeric'         => trans('pharmacy.numeric'),
            'micro_unit_id.numeric'         => trans('pharmacy.numeric'),
            'content_of_grand_unit.numeric' => trans('pharmacy.numeric'),
            'price_of_grand_unit.numeric'   => trans('pharmacy.numeric'),
            'discount.numeric'              => trans('pharmacy.numeric'),
            'supplier_id.numeric'           => trans('pharmacy.numeric'),
            'limit_of_deficiencies.numeric' => trans('pharmacy.numeric'),
            'recession_limit.numeric'       => trans('pharmacy.numeric'),

            // email
            'short_code.unique' => trans('pharmacy.unique_code'),


        ];
    }

}
