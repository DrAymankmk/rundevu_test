<?php

namespace App\Http\Resources\UserApp;

use App\Models\ClinicOffer;
use App\Models\DoctorAppointments;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->dateA,
            'status' => (int)$this->status,
        ];
    }
}
