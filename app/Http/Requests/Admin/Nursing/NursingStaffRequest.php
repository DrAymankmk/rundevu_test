<?php

namespace App\Http\Requests\Admin\Nursing;

use App\Http\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class NursingStaffRequest extends AdminRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          'nurse_id' => 'required',
          'point_id' => 'required',
          'notes' => 'required|string',
        ];
    }

    function messages()
    {
        return [
            'nurse_id.required' => trans('admin.nurse_id'),
            'point_id.required' => trans('admin.point_id'),
            'notes.required' => trans('admin.notes_faild'),
        ];
    }
}
