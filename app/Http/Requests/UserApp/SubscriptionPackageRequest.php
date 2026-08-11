<?php

namespace App\Http\Requests\UserApp;

use App\Http\Requests\Request;

class SubscriptionPackageRequest extends Request
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
            'package_id' => ['required', 'exists:packages,id'],
        ];
    }

    public function messages()
    {
        return [
            'package_id.required' => trans('user.packages.package_id'),
        ];
    }


}
