<?php

namespace App\Http\Requests\Admin\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRecordRequest extends FormRequest
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
        $id = request()->route('inventory_record');
        $rules =  [
            'note'         => 'required|string',
            'inventory_id' => 'required|array|min:1',
        ];

        if (isset($id)) {
            $rules['inventory_record_item_id'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            // required

            'note.required'         => trans('pharmacy.required'),
            'inventory_id.required' => trans('pharmacy.required'),
        ];
    }
}
