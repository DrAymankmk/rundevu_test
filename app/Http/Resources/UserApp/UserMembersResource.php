<?php

namespace App\Http\Resources\UserApp;

use App\Models\ClinicSpecialist;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMembersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $check_subscriptions = User::where('id', $this->parent_id)->where('expired_date', '>=', date('Y-m-d'))->exists();
        $parent_user = User::where('id', $this->parent_id)->select('phone')->first();
        return [
            'id'            => $this->id,
            'name'      =>  $this->name,
            'phone'      =>  $parent_user->phone,
            'image'      =>  $this->image,
            'gender'      => (int)$this->gender,
            'dob'      => $this->dob,
            'ID_Number'      => (int)$this->ID_Number,
            'status'      => $check_subscriptions,
            'qr_code'      =>  asset('media/users/qr_code/' . $this->ID_Number. '.' . 'png'),
            'file_number' => $this->file_number ?? "",
            'national_id' => $this->national_id ?? "",
        ];
    }
}
