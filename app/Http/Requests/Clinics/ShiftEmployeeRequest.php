<?php

namespace App\Http\Requests\Clinics;
use App\Http\Requests\Request;
class ShiftEmployeeRequest extends Request
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
            'employee_id' => ['required','exists:clinics,id'],
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => trans('messages.employees.id'),
        ];
    }


}
