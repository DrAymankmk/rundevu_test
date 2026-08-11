<?php

namespace App\Http\Requests\Clinics;

use App\Http\Requests\Request;

class UpdateOrCreateOffersRequest extends Request
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
            'discount' => ['required', 'integer'],
            'title_ar' => ['required', 'string'],
            'title_en' => ['required', 'string'],
            'offer_id' => ['nullable', 'exists:clinic_offers,id'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages()
    {
        return [
            'discount.required' => trans('messages.offers.discount'),
            'title_ar.required' => trans('messages.offers.title_ar'),
            'title_en.required' => trans('messages.offers.title_en'),
            'offer_id.required' => trans('messages.offers.id'),
        ];
    }


}
