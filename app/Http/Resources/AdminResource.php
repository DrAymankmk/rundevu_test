<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'gender'      => (int)$this->gender,
            'app_type'      => (int)$this->app_type,
            'dob'      =>  $this->dob,
        ];
    }
}
