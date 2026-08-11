<?php

namespace App\Http\Requests\ComplaintsBox;
use App\Http\Requests\Request;

class ComplaintsBoxRequest extends Request
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
            'reply' => ['required', 'string', 'max:5000'],
            'id'  => ['required', 'exists:complaint_boxes,id'],
        ];
    }

    public function messages()
    {
        return [
            'reply.required' => trans('messages.complaints_box.reply'),
            'id.required' => trans('messages.complaints_box.id'),
        ];
    }


}
