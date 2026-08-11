<?php

namespace App\Http\Resources\UserApp;
use App\Models\ClinicRating;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicPostsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->clinic_id,
            'image' => $this->image,
            'name' => $this->clinic->name,
            'rate' => ClinicRating::rate($this->clinic_id),
            'users_rate_count'=> ClinicRating::where('clinic_id', $this->clinic_id)->where('comment','!=',null)->count(),
            'info' => !empty($this->content) ? (string)$this->content : "",
            'lat' => !empty($this->clinic->lat) ? (string)$this->clinic->lat : "0.0",
            'lng' => !empty($this->clinic->lng) ? (string)$this->clinic->lng : "0.0",
            'address' => !empty($this->clinic->address) ? (string)$this->clinic->address : "0.0",
        ];
    }
}
