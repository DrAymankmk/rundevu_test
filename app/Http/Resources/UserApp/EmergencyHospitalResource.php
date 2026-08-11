<?php

namespace App\Http\Resources\UserApp;

use Illuminate\Http\Resources\Json\JsonResource;

class EmergencyHospitalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $request->header('lang') == 'en' ? $this->name_en : $this->name_ar,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'phone' => $this->phone,
            'image' => $this->image,
            'city_id' => (int) $this->city_id,
            'city' => $this->city ? [
                'id' => (int) $this->city->id,
                'name' => $request->header('lang') == 'en' ? $this->city->name_en : $this->city->name_ar,
                'name_ar' => $this->city->name_ar,
                'name_en' => $this->city->name_en,
            ] : null,
            'region_id' => $this->region_id ? (int) $this->region_id : null,
            'region' => $this->region ? [
                'id' => (int) $this->region->id,
                'name' => $request->header('lang') == 'en' ? $this->region->name_en : $this->region->name_ar,
                'name_ar' => $this->region->name_ar,
                'name_en' => $this->region->name_en,
            ] : null,
            'address' => (string) ($this->address ?? ''),
            'lat' => (string) ($this->lat ?? '0'),
            'lng' => (string) ($this->lng ?? '0'),
            'distance' => isset($this->distance) ? round((float) $this->distance, 2) : null,
            'call_url' => 'tel:' . $this->phone,
            'map_url' => 'https://www.google.com/maps/search/?api=1&query=' . $this->lat . ',' . $this->lng,
        ];
    }
}
