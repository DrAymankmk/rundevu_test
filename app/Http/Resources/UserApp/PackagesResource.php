<?php

namespace App\Http\Resources\UserApp;

use App\Models\ClinicOffer;
use Illuminate\Http\Resources\Json\JsonResource;

class PackagesResource extends JsonResource
{
    public function toArray($request)
    {
        $package_time = $this->duration . ' day';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'duration' => $this->duration,
            'price' => $this->price,
            'expired_date' => date("Y-m-d", strtotime('+' . $package_time, strtotime(date('Y-m-d'))) ),
            'type' => $this->price == 0  ? 1 : 2,
        ];
    }
}
