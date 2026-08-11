<?php

namespace App\Http\Resources\UserApp;

use App\Models\PatientService;
use App\Models\ReservationRate;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfirmReservationResource extends JsonResource
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
            'clinic_id'          => $this->clinic_id,
            'is_rated'          => ReservationRate::where('reservation_id',$this->id)->exists(),
            'clinic_name'          => optional($this->clinic)->name,
            'clinic_image'          => optional($this->clinic)->image,
            'reception_id'          => $this->reception_id,
            'reception_name'          => optional($this->reception)->name,
            'reception_image'          => optional($this->reception)->image,
            'doctor_id'          => $this->doctor_id,
            'doctor_name'          => optional($this->doctor)->name,
            'doctor_image'          => optional($this->doctor)->image,
            'specialties' => optional($this->doctor)->specialties ? "Obstetrics and Gynecology": "",
            'status_id'          => $this->status_id,
            'date'          => $this->date,
            'appointment'          => $this->appointment,
            'booking_flow'          => $this->booking_flow ?? 'instant',
            'payment_method'          => $this->payment_method ?? 'cash',
            'payment_status'          => (int) ($this->payment_status ?? 0),
            'payment_reference'          => (string) ($this->payment_reference ?? ''),
            'platform_commission_rate'          => (float) ($this->platform_commission_rate ?? 0),
            'platform_commission_amount'          => (float) ($this->platform_commission_amount ?? 0),
            'clinic_net_amount'          => (float) ($this->clinic_net_amount ?? 0),
            'settlement_direction'          => (string) ($this->settlement_direction ?? ''),
            'user_name'          => optional($this->user)->name,
            'user_image'          => optional($this->user)->image,
            'booking_number'      =>  asset('media/reservations/' . $this->booking_number. '.' . 'png')
        ];
    }
}
