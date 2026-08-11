<?php
namespace App\Http\Requests\UserApp\Auth;
use App\Http\Requests\Request;
class ActivateAccount extends Request
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
            'code' => 'required',
            'phone' => ['required', 'regex:/^((\+|00)?0?5[0-9]{8})$/'],
        ];
    }

    public function messages()
    {
        return [
            'code.required' => trans('messages.auth.code'),
            'phone.required' => trans('messages.auth.phone'),
        ];
    }
}
