<?php

namespace App\Http\Requests\UserApp;

use App\Http\Requests\Request;

class DoctorAppointment extends Request
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
            'id' => 'required|exists:clinics,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => trans('user.doctors.id'),
        ];
    }


}
