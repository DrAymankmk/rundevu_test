<?php

namespace App\Http\Requests\PermissionTypes;
use App\Http\Requests\Request;
class PermissionRequest extends Request
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
            'reason' => ['required', 'string','min:5'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:30000'],
            'permission_id' => ['required', 'exists:permissions_types,id'],
        ];
    }

    public function messages()
    {
        return [
            'reason.required' => trans('messages.permissions.reason'),
            'image.required' => trans('messages.auth.image'),
            'permission_id.required' => trans('messages.permissions.permission_id'),
        ];
    }


}
