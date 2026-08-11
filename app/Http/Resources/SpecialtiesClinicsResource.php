<?php

namespace App\Http\Resources;

use App\Models\ClinicSpecialist;
use App\Models\Specialty;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialtiesClinicsResource extends JsonResource
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
            'specialty_id'            => (int)$this->specialty_id,
            'is_selected' => ClinicSpecialist::where('clinic_id',$request->employee_id)->where('specialty_id',$this->specialty_id)->exists(),
            'name'      => $request->header('lang') == 'en' ? $this->specialties->name_en : $this->specialties->name_ar,
//            'sub_specialties'            => Specialty::where('parent_id', $this->specialty_id)->select('id','name_'. $request->header('lang').' as name')->get(),
            'sub_specialties' => ClinicSpecialist::clinic_specialties($request->employee_id,$this->specialty_id,$request->header('lang')),
        ];
    }
}
