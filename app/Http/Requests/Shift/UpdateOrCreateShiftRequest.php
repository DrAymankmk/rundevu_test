<?php

namespace App\Http\Requests\Shift;

use App\Http\Requests\Request;

class UpdateOrCreateShiftRequest extends Request
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
            'department_id' => ['required', 'exists:app_types,id'],
            'name' => ['required', 'string'],
            'minute_allow_delay' => ['required', 'integer'],
            'time_from'  => ['required', 'date_format:H:i'],
            'time_to'  => ['required', 'date_format:H:i'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => trans('messages.department.id'),
            'name.required' => trans('messages.shift.content'),
            'shift_id.required' => trans('messages.shift.id'),
            'time_from.required' => trans('messages.shift.time_from'),
            'time_to.required' => trans('messages.shift.time_to'),
            'minute_allow_delay.required' => trans('messages.shift.minute_allow_delay'),
        ];
    }


}
