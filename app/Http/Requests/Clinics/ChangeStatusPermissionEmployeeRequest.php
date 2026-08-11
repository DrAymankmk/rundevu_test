<?php

namespace App\Http\Requests\Clinics;
use App\Http\Requests\Request;
class ChangeStatusPermissionEmployeeRequest extends Request
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
            'permission_id' => ['required','exists:permissions_requests,id'],
            'status' => ['required', 'in:1,2'],
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => trans('messages.employees.id'),
            'status.required' => trans('messages.attendance.status'),
        ];
    }


}
