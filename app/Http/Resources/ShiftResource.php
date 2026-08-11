<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
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
            'name'      => $this->name,
            'time_from'      => date('H:i',strtotime($this->time_from)),
            'time_to'      => date('H:i',strtotime($this->time_to)),
            'minute_allow_delay'      => $this->minute_allow_delay,
        ];
    }
}
