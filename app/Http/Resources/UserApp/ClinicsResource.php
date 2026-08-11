<?php

namespace App\Http\Resources\UserApp;

use App\Models\ClinicOffer;
use App\Models\ClinicRating;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'rate' => ClinicRating::rate($this->id),
            'users_rate_count'=> ClinicRating::where('clinic_id', $this->id)->where('comment','!=',null)->count(),
            'info' => !empty($this->info) ? (string)$this->info : "",
            'lat' => !empty($this->lat) ? (string)$this->lat : "0.0",
            'lng' => !empty($this->lng) ? (string)$this->lng : "0.0",
            'address' => !empty($this->address) ? (string)$this->address : "0.0",
            'booking_policy' => $this->bookingPolicy(),
            'social_media' => [
                'facebook' => (string) ($this->facebook_url ?? ''),
                'instagram' => (string) ($this->instagram_url ?? ''),
                'tiktok' => (string) ($this->tiktok_url ?? ''),
                'snapchat' => (string) ($this->snapchat_url ?? ''),
                'youtube' => (string) ($this->youtube_url ?? ''),
            ],
        ];
    }
}
