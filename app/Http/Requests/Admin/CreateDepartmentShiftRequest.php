<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class CreateDepartmentShiftRequest extends AdminRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'time_from' => 'required',
            'time_to' => 'required',
            'minute_allow_delay' => 'required',
        ];
    }

    function messages()
    {
        return [
            'name.required' => trans('admin.shifts.name'),
            'time_from.required' =>trans('admin.shifts.time_from'),
            'time_to.required' => trans('admin.shifts.time_to'),
            'minute_allow_delay.required' => trans('admin.shifts.minute_allow_delay'),
        ];
    }
}
