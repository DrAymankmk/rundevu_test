<?php

namespace App\Http\Requests\UserApp;

use App\Http\Requests\Request;

/**
 * Class ManageSettingsRequest.
 */
class ReservationRateRequest extends Request
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
            'reservation_id' => 'required|exists:reservations,id',
            'rate_value'     => 'required|integer|min:1|max:5',
            'comment'        => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'reservation_id.required' => trans('messages.reservation_id_required'),
            'reservation_id.exists'   => trans('messages.reservation_id_not_found'),

            'rate_value.required' => trans('messages.rate_required'),
            'rate_value.integer'  => trans('messages.rate_integer'),
            'rate_value.min'      => trans('messages.rate_min'),
            'rate_value.max'      => trans('messages.rate_max'),

            'comment.string'      => trans('messages.comment_string'),
        ];
    }


}
