<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintsBoxResource extends JsonResource
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
            'name'          => $this->users->name,
            'ID_Number'     => $this->users->ID_Number,
            'phone'      => $this->users->phone,
            'image'      => $this->users->image,
            'online'      => 1,
            'created_at'      => $this->users->created_at->format('h:i a'),
            'complain'      => $this->complain,
            'reply'      => !empty($this->reply) ? $this->reply : "",

        ];
    }
}
