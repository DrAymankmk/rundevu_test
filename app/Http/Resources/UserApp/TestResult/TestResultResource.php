<?php

namespace App\Http\Resources\UserApp\TestResult;

use Illuminate\Http\Resources\Json\JsonResource;

class TestResultResource extends JsonResource
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
            'clinic_name'          => $this->clinic->name,
            'clinic_image'          => $this->clinic->image,
            'doctor_name'          => $this->doctor->name ?? "",
            'service_name'          => $request->header('lang') == 'en' ? $this->services->name_en ?? "" : $this->services->name_ar ?? "",
            'date'          => date('Y-m-d',strtotime($this->created_at)),
        ];
    }
}
