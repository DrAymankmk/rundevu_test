<?php

namespace App\Http\Requests\Profile;
use App\Http\Requests\Request;

class UpdateRequest extends Request
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
            'date_created'  => ['required', 'date_format:Y-m-d'],
            'info' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'communication_officer' => ['required', 'string', 'max:250'],
            'city_id'  => ['required', 'exists:cities,id'],
            'lat'                  => ['required', 'string', 'max:200'],
            'lng'                 => ['required', 'string', 'max:200'],
            'address'   => ['required', 'string', 'max:250'],
            'image' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:30000'],
            'qr_code' => 'nullable',
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'snapchat_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('messages.auth.name'),
            'date_created.required' => trans('messages.auth.date_created'),
            'info.required' => trans('messages.auth.info'),
            'phone.required' => trans('messages.phone'),
            'email.required' => trans('messages.email'),
            'communication_officer.required' => trans('messages.auth.communication_officer'),
            'city_id.required' => trans('messages.auth.city_id'),
            'address.required' => trans('messages.auth.address'),
            'lat.required' => trans('messages.auth.lat'),
            'lng.required' => trans('messages.auth.lng'),
            'image.required' => trans('messages.auth.image'),
            'qr_code.required' => trans('messages.auth.qr_code'),
        ];
    }


}
