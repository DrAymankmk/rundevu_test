<?php

namespace App\Http\Resources\UserApp;

use App\Models\Clinic;
use App\Models\ReservationRate;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClinicDoctorsResource extends JsonResource
{
    public function toArray($request)
    {
        $clinic_location = Clinic::where('id', $this->parent_id)->first();
        $lang = $request->header('lang') == 'en' ? 'en' : 'ar';
        $bioAr = (string) ($this->info_ar ?? '');
        $bioEn = (string) ($this->info ?? '');
        $terms = Setting::where(['app_type'=>3,'settings_type' => 'terms'])->value('content_'.$lang);
        $rateCount = ReservationRate::where('doctor_id', $this->id)->whereNotNull('comment')->count();
        $rate = $rateCount > 0
            ? (string) round(ReservationRate::where('doctor_id', $this->id)->whereNotNull('comment')->avg('rate_value'), 1)
            : '0';

        return [
            'id' => $this->id,
            'type' => (string) ($this->doctor_list_type ?? ''),
            'image' => $this->image,
            'name' => $this->name,
            'phone' => $this->phone,
            'bio_ar' => $bioAr,
            'bio_en' => $bioEn,
            'short_bio_ar' => $this->shortBio($bioAr),
            'short_bio_en' => $this->shortBio($bioEn),
            'consultation_price' => (string) ($this->consultation_price ?? '0'),
            'rate' => $rate,
            'rate_count' => $rateCount,
            'reservations_count' => (int) ($this->reservations_count ?? DB::table('reservations')->where('doctor_id', $this->id)->count()),
            'degree' => [
                'id' => optional($this->degree)->id,
                'name_ar' => (string) (optional($this->degree)->name_ar ?? ''),
                'name_en' => (string) (optional($this->degree)->name_en ?? ''),
            ],
            'specialties' => $this->specialtyNames($this, $lang),
            'specialty_id' => optional($this->specialty->first())->specialty_id,
            'specialties_list' => $this->specialties->map(function ($item) use ($lang) {
                return $this->specialtyItem($item, $lang);
            })->values(),
            'sub_specialties' => $this->sub_specialties->map(function ($item) use ($lang) {
                return [
                    'specialty_id' => $item->specialty_id,
                    'name' => $this->specialtyName($item, $lang),
                ];
            })->values(),
            'lat' => !empty($clinic_location->lat) ? (string)$clinic_location->lat : "0.0",
            'lng' => !empty($clinic_location->lng) ? (string)$clinic_location->lng : "0.0",
            'terms' => (string) ($terms ?? ''),
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

    private function specialtyItem($item, $lang)
    {
        return [
            'id' => $item->id,
            'specialty_id' => $item->specialty_id,
            'name' => $this->specialtyName($item, $lang),
        ];
    }

    private function specialtyName($item, $lang)
    {
        $nameColumn = $lang == 'en' ? 'name_en' : 'name_ar';

        return (string) (optional($item->specialties)->{$nameColumn} ?? '');
    }

    private function shortBio($bio)
    {
        return Str::limit(trim(strip_tags((string) $bio)), 160, '');
    }
}
