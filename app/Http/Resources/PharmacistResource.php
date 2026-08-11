<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PharmacistResource extends JsonResource
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
            'name'      =>  $this->name,
            'email'      =>  $this->email,
            'phone'      =>  $this->phone,
            'image'      =>  $this->image,
            'gender'      => $this->gender,
            'specialist_ids'      =>  $this->specialties,
            'working_days'      =>  $this->working_days,
        ];
    }
}
