<?php

namespace App\Http\Resources\UserApp;

use App\Models\Clinic;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicDoctorsResource extends JsonResource
{
    public function toArray($request)
    {
        $clinic_location = Clinic::where('id', $this->parent_id)->first();
        $terms = $request->header('lang') == 'en' ? $this->info : $this->info_ar;
        $terms = Setting::where(['app_type'=>3,'settings_type' => 'terms'])->pluck('content_'.$request->header('lang') ?? 'ar')->first();

        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'phone' => $this->phone,
            'bio_ar' => (string) ($this->info_ar ?? ''),
            'bio_en' => (string) ($this->info ?? ''),
            'consultation_price' => (string) ($this->consultation_price ?? '0'),
            'specialties' => $this->specialtyNames($this, $request->header('lang')),
            'specialty_id' => optional($this->specialty->first())->specialty_id,
            'sub_specialties' => $this->sub_specialties->map(function ($item) {
                return [
                    'specialty_id' => $item->specialty_id
                ];
            }),
            'lat' => !empty($clinic_location->lat) ? (string)$clinic_location->lat : "0.0",
            'lng' => !empty($clinic_location->lng) ? (string)$clinic_location->lng : "0.0",
            'terms' => $terms,
            'address' => !empty($clinic_location->address) ? (string)$clinic_location->address : "0.0",
            'booking_policy' => $this->bookingPolicy(),
        ];
    }

    private function specialtyNames($doctor, $lang)
    {
        $nameColumn = $lang == 'en' ? 'name_en' : 'name_ar';

        return $doctor->specialties
            ->map(function ($clinicSpecialty) use ($nameColumn) {
                return $clinicSpecialty->specialties ? $clinicSpecialty->specialties->{$nameColumn} : null;
            })
            ->filter()
            ->implode(', ');
    }
}
