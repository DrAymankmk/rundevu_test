<?php

namespace App\Http\Resources\UserApp;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunicationsMessagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!empty($this->clinic_id)) {
            $name = $this->clinics->name;
            $phone = $this->clinics->phone;
            $image = $this->clinics->image;
            $clinic_id = (int)$this->clinic_id;
            $type = 1;
        } else {
            $name = 'Admin';
            $phone = "";
            $image = "";
            $type = 2;
            $clinic_id = 0;
        }
        return [
            'id'            => $this->id,
            'name'          => $name,
            'complain_Number'     => rand(00000,99999),
            'phone'      => $phone,
            'image'      => $image,
            'clinic_id'      => $clinic_id,
            'online'      => 1,
            'type'      => $type,
            'created_at'      => $this->created_at->format('Y-m-d h:i a'),
            'complain'      => $this->complain,
            'reply'      => !empty($this->reply) ? $this->reply : "",

        ];
    }
}
