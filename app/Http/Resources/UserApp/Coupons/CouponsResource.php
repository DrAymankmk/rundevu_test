<?php

namespace App\Http\Resources\UserApp\Coupons;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponsResource extends JsonResource
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
            'title' => $this->title,
            'coupon_number' => $this->coupon_number,
            'qr_code'      =>  asset('media/coupons/' . $this->coupon_number. '.' . 'png'),
        ];
    }
}
