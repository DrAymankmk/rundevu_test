<?php

namespace App\Http\Requests\Clinics;

use App\Http\Requests\Request;

class UpdateOrCreateNewEmployeeRequest extends Request
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
            'name' => ['required', 'string', 'max:250'],
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'password' => 'required_without:id|min:6',
            'gender' => ['required', 'in:1,2'],
            'app_type' => ['required', 'exists:app_types,id'],
            'id' => ['nullable', 'exists:clinics,id'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:30000'],
//            'specialist_ids' => 'required_if:app_type,2|required_if:app_type,3||required_if:app_type,4||required_if:app_type,5|array|min:1',
            'degree_id' => ['required_if:app_type,3', 'exists:doctor_degrees,id'],
            'ID_Number' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('messages.auth.name'),
            'phone.required' => trans('messages.phone'),
            'email.required' => trans('messages.email'),
            'password.required' => trans('messages.password'),
            'gender.required' => trans('messages.employees.gender'),
            'app_type.required' => trans('messages.employees.app_type'),
            'image.required' => trans('messages.auth.image'),
            'specialist_ids.required' => trans('messages.employees.specialist_ids'),
            'degree_id.required' => trans('messages.employees.degree_id'),
        ];
    }


}
