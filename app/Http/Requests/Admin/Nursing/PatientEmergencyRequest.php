<?php

namespace App\Http\Requests\Admin\Nursing;

use App\Http\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class PatientEmergencyRequest extends AdminRequest
{

          public function authorize()
          {
                    return true;
          }

          public function rules()
          {
                    return [
                              'name' => 'required',
                              'father_name' => 'required',
                              'Grandfather_name' => 'required',
                              'family_name' => 'required',
                              'phone' => 'required|unique:users,phone',
                              'address_1' => 'required',
                              'city_id' => 'required',
                              'gender' => 'required',
                    ];
          }

          function messages()
          {
                    return [
                              'name.required' => trans('admin.name_required'),
                              'father_name.required' => trans('admin.father_name_required'),
                              'Grandfather_name.required' => trans('admin.Grandfather_name_required'),
                              'family_name.required' => trans('admin.family_name_required'),
                              'phone.required' => trans('admin.phone_required'),
                              'phone.unique' => trans('admin.phone_unique'),
                              'address_1.required' => trans('admin.address_1required'),
                              'city_id.required' => trans('admin.city_id_required'),
                              'gender.required' => trans('admin.gender_required'),
                    ];
          }
}
