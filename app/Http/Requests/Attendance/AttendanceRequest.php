<?php

namespace App\Http\Requests\Attendance;
use App\Http\Requests\Request;
class AttendanceRequest extends Request
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
            'date_from'  => ['required', 'date_format:Y-m-d'],
            'date_to'  => ['required', 'date_format:Y-m-d'],
            'department_id' => ['required', 'exists:app_types,id'],
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => trans('messages.department.id'),
            'date_from.required' => trans('messages.staff.date_from'),
            'date_to.required' => trans('messages.staff.date_to'),
        ];
    }


}
