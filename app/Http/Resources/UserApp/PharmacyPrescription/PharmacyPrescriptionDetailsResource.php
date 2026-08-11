<?php

namespace App\Http\Resources\UserApp\PharmacyPrescription;

use App\Models\PharmacyPrescriptionDetails;
use Illuminate\Http\Resources\Json\JsonResource;

class PharmacyPrescriptionDetailsResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->user->name,
            'image' => $this->user->image,
            'gender' => $this->user->gender,
            'ID_Number' => $this->user->ID_Number,
            'date' => $this->user->dob,
            'subscribe_end' => $this->user->expired_date,
            'qr_code_user'      =>  asset('media/users/qr_code/' . $this->user->ID_Number. '.' . 'png'),
            'qr_code_doctor'      =>  asset('media/clinics/qr_code/' . $this->clinic->ID_Number. '.' . 'png'),
            'qr_code_clinic'      =>  asset('media/clinics/qr_code/' . $this->doctor->ID_Number. '.' . 'png'),
            'phone' => $this->user->phone,
            'diagnosis' => $this->diagnosis,
            'drugs' => PharmacyPrescriptionDetails::prescription_details($this->drugs),
        ];
    }
}
