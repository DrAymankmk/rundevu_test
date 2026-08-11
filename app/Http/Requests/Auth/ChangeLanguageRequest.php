<?php

namespace App\Http\Requests\Auth;

use function App\Helpers\access;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;


/**
 * Class ManageSettingsRequest.
 */
class ChangeLanguageRequest extends Request
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
        $jwt_token = $this->input('jwt_token');
        $user_id = $this->input('user_id');
        return [
            'user_id'       => 'required|exists:users,id',
            'lang'       => 'required',
            'jwt_token' => [
                    'required',
                    Rule::exists('users')->where(function ($query) use ($user_id,$jwt_token) {
                        $query->where('id', $user_id);
                        $query->where('jwt_token', $jwt_token);
                })
            ]
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => trans('validation.user_id'),
            'lang.required' => trans('validation.lang'),
        ];
    }
}
