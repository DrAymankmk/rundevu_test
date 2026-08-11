<?php

namespace App\Http\Requests\Departments;
use App\Http\Requests\Request;
class UpdateOrCreateDepartmentRequest extends Request
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
            'name_en' => ['required', 'string'],
            'name_ar' => ['required', 'string'],
            'department_id' => ['nullable', 'exists:app_types,id'],
        ];
    }

    public function messages()
    {
        return [
            'name_en.required' => trans('messages.department.name_en'),
            'name_ar.required' => trans('messages.department.name_ar'),
            'department_id.required' => trans('messages.department.id'),
        ];
    }


}
