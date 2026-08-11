<?php

namespace App\Http\Resources\UserApp;

use Illuminate\Http\Resources\Json\JsonResource;

class WaitingListResource extends JsonResource
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
            'image'          => $this->user->image ?? "",
            'user_name'          => $this->user->name,
            'age'          => \Carbon\Carbon::parse($this->user->dob)->diff(\Carbon\Carbon::now())->y,
            'order'          => 1,
            'color'          => '#ff0000',
        ];
    }
}
