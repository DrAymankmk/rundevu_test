<?php

namespace App\Http\Resources;

use App\Models\ClinicSpecialist;
use Illuminate\Http\Resources\Json\JsonResource;

class NewEmployeeResource extends JsonResource
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
            'specialist_ids'      =>  $this->specialties,
            'specialty'      =>  (int)ClinicSpecialist::where('clinic_id',$this->id)->where('type',1)->pluck('specialty_id')->first(),
            'sub_specialty'      =>  (int)ClinicSpecialist::where('clinic_id',$this->id)->where('type',2)->pluck('specialty_id')->first(),
//            'specialization'      =>  !empty($this->specialization) ? $this->specialization : "" ,
            'degree_id'      =>  !empty($this->degree_id) ? $this->degree_id : 0,
            'ID_Number'      =>  !empty($this->ID_Number) ? (int)$this->ID_Number : "",
        ];
    }
}
