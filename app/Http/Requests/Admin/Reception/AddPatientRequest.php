<?php

namespace App\Http\Requests\Admin\Reception;

use App\Http\Requests\AdminRequest;
use Illuminate\Foundation\Http\FormRequest;

class AddPatientRequest extends AdminRequest
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

    public function rules()
    {
        return [
            'name_ar' => 'required',
            'name_en' => 'required',
            'image' => 'required|mimes:jpeg,bmp,png,jpg',
            'category_id'=>'required|exists:categories,id',
            'subcategory_id'=>'required|exists:categories,id',
            'brand_id'=>'required|exists:brands,id',
            'price_after'=>'required|numeric',
            'description_en'=>'required',
            'description_ar'=>'required',
            'type'=>'required',
            'delivery'=>'required',
            'gender'=>'required',
            'qty'=>'required|integer',
        ];
    }

    function messages()
    {
        return [
            'name_ar.required' => trans('admin.name_ar'),
            'name_en.required' => trans('admin.name_en'),
            'image.required' => trans('admin.image'),
            'subcategory_id.required' => trans('admin.subcategory_id'),
            'brand_id.required' => trans('admin.brand_id'),
            'price_after.required' => trans('admin.price_after'),
            'description_en.required' => trans('admin.description_en'),
            'description_ar.required' => trans('admin.description_ar'),
            'gender.required' => trans('admin.gender_request'),
            'type.required' => trans('admin.type_request'),
            'qty.required' => trans('admin.qty_request'),
        ];
    }
}
