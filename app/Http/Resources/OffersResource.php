<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OffersResource extends JsonResource
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
            'title'      => $request->header('lang') == 'en' ? $this->title_en : $this->title_ar,
            'title_ar'      => $this->title_ar,
            'title_en'      => $this->title_en,
            'discount'      => $this->discount,
            'specialty_id'   => $this->specialty_id ? (int) $this->specialty_id : null,
            'specialty'      => $this->specialty ? ($request->header('lang') == 'en' ? $this->specialty->name_en : $this->specialty->name_ar) : '',
            'start_date'     => $this->start_date,
            'end_date'       => $this->end_date,
        ];
    }
}
