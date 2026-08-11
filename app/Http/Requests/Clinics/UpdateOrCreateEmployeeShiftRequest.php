<?php

namespace App\Http\Requests\Clinics;

use App\Http\Requests\Request;

class UpdateOrCreateEmployeeShiftRequest extends Request
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
            'id' => ['nullable', 'exists:shift_employees,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
            'employee_id' => ['nullable', 'exists:clinics,id'],
//            'dateA'   => 'required|array|min:1|date_format:Y-m-d|after_or_equal:1920-01-01',
            'dateA'   => 'required|array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => trans('messages.shift.id'),
            'shift_id.required' => trans('messages.shift.id'),
            'employee_id.required' => trans('messages.employees.id'),
            'dateA.required' => trans('messages.auth.date_created'),
        ];
    }


}
