<?php

namespace App\Http\Requests\UserApp\Auth;

use App\Http\Requests\Request;


/**
 * Class ManageSettingsRequest.
 */
class CheckPhoneRequest extends Request
{
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
            // 'phone' => 'required',
              'phone' => ['required', 'regex:/^((\+|00)?9660?5[0-9]{8}|0?5[0-9]{8})$/'],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => trans('messages.auth.phone'),
        ];
    }
}
