<?php

namespace App\Http\Requests\UserApp;

use App\Http\Requests\Request;

/**
 * Class ManageSettingsRequest.
 */
class CreateReservationChatRequest extends Request
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
            'message' => 'nullable',
            'file' => 'nullable',
            'record' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'reservation_id.required' => 'رقم الحجز مطلوب',
            'message.required' => 'message Required',
        ];
    }


}
