<?php

namespace App\Http\Requests\UserApp\Clinics;

use App\Http\Requests\Request;

class ClinicRequest extends Request
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
            'id' => ['nullable', 'exists:clinics,id'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => trans('user.userApp.clinic.id'),
        ];
    }


}
