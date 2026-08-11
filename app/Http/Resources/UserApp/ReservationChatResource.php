<?php

namespace App\Http\Resources\UserApp;

use App\Models\ReservationChat;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'created_date'            => date('D, d / m, Y',strtotime($this->created_at)),
            'chats'          =>ReservationChat::get_messages_chat($request,date('Y-m-d',strtotime($this->created_at))),
//            'message'          => !empty($this->message) ? $this->message : "",
//            'file'          => !empty($this->file) ? $this->file : "",
//            'record'          => !empty($this->record) ? $this->record : "",
//            'sender_type'          => $this->sender_type,
//            'media_flag'          => $this->media_flag,
//            'time'          => date('h:i',strtotime($this->created_at)),
        ];
    }
}
