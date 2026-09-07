<?php

namespace App\Http\Resources\UserApp;

use App\Models\Clinic;
use App\Models\ClinicRating;
use App\Models\ReservationRate;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DoctorDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('lang') == 'en' ? 'en' : 'ar';
        $clinic = $this->owner;
        $bioAr = (string) ($this->info_ar ?? '');
        $bioEn = (string) ($this->info ?? '');
        $rates = ReservationRate::with('users')
            ->where('doctor_id', $this->id)
            ->whereNotNull('comment')
            ->orderBy('id', 'desc')
            ->get();
        $rateCount = $rates->count();
        $rate = $rateCount > 0 ? (string) round($rates->avg('rate_value'), 1) : '0';
        $termsColumn = 'content_' . $lang;
        $terms = Setting::where(['app_type' => 3, 'settings_type' => 'terms'])->value($termsColumn);

        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'image' => $this->image,
            'name' => $this->name,
            'phone' => (string) ($this->phone ?? ''),
            'email' => (string) ($this->email ?? ''),
            'gender' => (string) ($this->gender ?? ''),
            'bio_ar' => $bioAr,
            'bio_en' => $bioEn,
            'short_bio_ar' => $this->shortBio($bioAr),
            'short_bio_en' => $this->shortBio($bioEn),
            'consultation_price' => (string) ($this->consultation_price ?? '0'),
            'rate' => $rate,
            'rate_count' => $rateCount,
            'clinic_rate' => $clinic ? ClinicRating::rate($clinic->id) : '0',
            'degree' => [
                'id' => optional($this->degree)->id,
                'name_ar' => (string) (optional($this->degree)->name_ar ?? ''),
                'name_en' => (string) (optional($this->degree)->name_en ?? ''),
            ],
            'clinic' => [
                'id' => optional($clinic)->id,
                'name' => (string) (optional($clinic)->name ?? ''),
                'image' => optional($clinic)->image,
                'phone' => (string) (optional($clinic)->phone ?? ''),
                'lat' => !empty(optional($clinic)->lat) ? (string) $clinic->lat : '0.0',
                'lng' => !empty(optional($clinic)->lng) ? (string) $clinic->lng : '0.0',
                'address' => !empty(optional($clinic)->address) ? (string) $clinic->address : '0.0',
            ],
            'specialties' => $this->specialtyNames($this->specialties, $lang),
            'specialties_list' => $this->specialties->map(function ($item) use ($lang) {
                return $this->specialtyItem($item, $lang);
            })->values(),
            'sub_specialties' => $this->sub_specialties->map(function ($item) use ($lang) {
                return $this->specialtyItem($item, $lang);
            })->values(),
            'terms' => (string) ($terms ?? ''),
            'booking_policy' => $this->bookingPolicy(),
            'visitor_rating' => $rates->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => (string) (optional($item->users)->name ?? ''),
                    'image' => optional($item->users)->image,
                    'comment' => (string) ($item->comment ?? ''),
                    'rate' => (string) $item->rate_value,
                ];
            })->values(),
        ];
    }

    private function specialtyNames($specialties, $lang)
    {
        $nameColumn = $lang == 'en' ? 'name_en' : 'name_ar';

        return $specialties
            ->map(function ($clinicSpecialty) use ($nameColumn) {
                return $clinicSpecialty->specialties ? $clinicSpecialty->specialties->{$nameColumn} : null;
            })
            ->filter()
            ->implode(', ');
    }

    private function specialtyItem($item, $lang)
    {
        $nameColumn = $lang == 'en' ? 'name_en' : 'name_ar';

        return [
            'id' => $item->id,
            'specialty_id' => $item->specialty_id,
            'name' => (string) (optional($item->specialties)->{$nameColumn} ?? ''),
        ];
    }

    private function shortBio($bio)
    {
        return Str::limit(trim(strip_tags((string) $bio)), 160, '');
    }
}
