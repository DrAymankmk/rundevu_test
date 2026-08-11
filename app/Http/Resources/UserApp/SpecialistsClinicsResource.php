<?php

namespace App\Http\Resources\UserApp;

use App\Models\ClinicSpecialist;
use App\Models\Specialty;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialistsClinicsResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'      => $request->header('lang') == 'en' ? $this->name_en : $this->name_ar,
            'sub_specialties'   => Specialty::where('parent_id', $this->id)->select('id','name_'. $request->header('lang').' as name')->get(),
        ];
    }
}
