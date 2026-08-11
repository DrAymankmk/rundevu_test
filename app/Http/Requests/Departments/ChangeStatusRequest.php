<?php

namespace App\Http\Requests\Departments;
use App\Http\Requests\Request;
class ChangeStatusRequest extends Request
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
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => trans('messages.department.id'),
        ];
    }


}
