<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentsResource extends JsonResource
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
            'name'      => $request->header('lang') == 'en' ? $this->name_en : $this->name_ar,
            'type'      => $this->type,
            'name_en'      => $this->name_en,
            'name_ar'      => $this->name_ar,
            'status'      => (int)$this->status,
        ];
    }
}
