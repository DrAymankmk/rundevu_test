<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class PointsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $request->header('lang') == 'en' ? $this->content_en : $this->content_ar,
            'point' => $this->point,
            'created_at' => $this->created_at->format('F jS'),
        ];
    }
}
