<?php

namespace App\Http\Requests\Specialties;

use App\Http\Requests\Request;

class UpdateOrCreateSpecialtyRequest extends Request
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
            'id' => ['nullable', 'exists:clinic_specialists,id'],
            'specialty_id' => ['required', 'exists:specialties,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => trans('messages.specialties.id'),
            'specialty_id.required' => trans('messages.specialties.id'),
        ];
    }


}
