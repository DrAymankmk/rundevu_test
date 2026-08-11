<?php

namespace App\Http\Requests\ClinicsPosts;

use App\Http\Requests\Request;

class UpdateOrCreateRequest extends Request
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
            'image' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:10000'],
            'post_content' => ['required', 'string'],
            'post_id' => ['nullable', 'exists:posts,id'],
        ];
    }

    public function messages()
    {
        return [
            'post_content.required' => trans('messages.posts.content'),
            'post_id.required' => trans('messages.posts.id'),
        ];
    }


}
