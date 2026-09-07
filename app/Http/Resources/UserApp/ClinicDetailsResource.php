<?php

namespace App\Http\Resources\UserApp;

use App\Models\Clinic;
use App\Models\ClinicRating;
use App\Models\Reservations;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        $authorization = request()->header('Authorization');
        $user = User::checkJwtAuth($authorization);
        $user_id = $user->id ?? null;
        $currentPackage = $this->currentPackage;
        $contractModel = optional($this->contract)->contract_model ?? 'cash_commission';
        $bookingPolicy = $this->bookingPolicy();

        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'rate' => ClinicRating::rate($this->id),
            'users_rate_count'=>ClinicRating::where('clinic_id', $this->id)->where('comment','!=',null)->count(),
//            'is_rate'=> ClinicRating::where('clinic_id', $this->id)->where('comment','!=',null)->where('user_id',$user->id)->exists(),
            'is_rate'=> Reservations::where('clinic_id',$this->id)->where('status_id',6)->where('user_id',$user_id)->exists(),
            'info' => !empty($this->info) ? (string)$this->info : "",
            'lat' => !empty($this->lat) ? (string)$this->lat : "0.0",
            'lng' => !empty($this->lng) ? (string)$this->lng : "0.0",
            'address' => !empty($this->address) ? (string)$this->address : "0.0",
            'package' => [
                'id' => $currentPackage->id ?? null,
                'name_ar' => $currentPackage->name_ar ?? '',
                'name_en' => $currentPackage->name_en ?? '',
                'duration' => $currentPackage->duration ?? null,
                'end_date' => $this->package_end_date,
            ],
            'contract_model' => $contractModel,
            'booking_policy' => $bookingPolicy,
            'reservation_options' => [
                'booking_flow' => $bookingPolicy['booking_flow'],
                'action' => $bookingPolicy['booking_action'],
                'label_ar' => $bookingPolicy['booking_action_label_ar'],
                'label_en' => $bookingPolicy['booking_action_label_en'],
                'instructions_ar' => $bookingPolicy['booking_instructions_ar'],
                'instructions_en' => $bookingPolicy['booking_instructions_en'],
                'requires_doctor_selection' => $bookingPolicy['requires_doctor_selection'],
                'requires_appointment_selection' => $bookingPolicy['requires_appointment_selection'],
                'allows_instant_booking' => $bookingPolicy['allows_instant_booking'],
                'allows_booking_request' => $bookingPolicy['allows_booking_request'],
            ],
            'social_media' => [
                'facebook' => (string) ($this->facebook_url ?? ''),
                'instagram' => (string) ($this->instagram_url ?? ''),
                'tiktok' => (string) ($this->tiktok_url ?? ''),
                'snapchat' => (string) ($this->snapchat_url ?? ''),
                'youtube' => (string) ($this->youtube_url ?? ''),
            ],
            'reception_staff' => $this->reception_staff,
            'medical_staff' => ClinicDoctorsResource::collection($this->medical_staff),
            'visitor_rating' => Clinic::visitor_rating($this->id),
        ];
    }
}
