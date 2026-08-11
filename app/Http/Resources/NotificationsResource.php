<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
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
            'title'      => $this->title,
            'message'      => $this->message,
            'flag'      => (int)$this->flag,
            'url'      => !empty($this->url) ? $this->url : '',
            'coupon_status'      => (int)$this->coupon_status,
            'notification_image'      => $this->image,

        ];
    }
}
