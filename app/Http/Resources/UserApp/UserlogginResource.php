<?php

namespace App\Http\Resources\UserApp;

use App\Models\ComplaintBox;
use Illuminate\Http\Resources\Json\JsonResource;

class UserlogginResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'email' => $this->email ?? "",
            'phone' => $this->phone ?? "",
            'ID_Number' => (int)$this->ID_Number,
            'gender' => (int)$this->gender,
            'city_id' => (int)$this->city_id,
            'referral_code' => !empty($this->referral_code) ? $this->referral_code : "",
            'lat' => (string)$this->lat,
            'lng' => (string)$this->lng,
            'address' => $this->address,
            'package_id' => !empty($this->package_id) ? (int)$this->package_id : 0,
            'notifications_count' =>  0,
            'dob' =>  $this->dob,
            'amount' =>  "0",
            'complains_count' =>  ComplaintBox::where('user_id',$this->id)->count(),
            'expired_date' => !empty($this->expired_date) ? $this->expired_date : date('Y-m-d',strtotime($this->created_at)),
            'qr_code'      =>  asset('media/users/qr_code/' . $this->ID_Number. '.' . 'png'),
            'file_number' => $this->file_number ?? "",
            'bill_number' => $this->bill_number ?? "",
            'national_id' => $this->national_id ?? "",
            'company_name' => $request->header('lang') == 'ar' ? $this->company->name_ar ?? "" : $this->company->name_en ?? "",
            'class_name' => $request->header('lang') == 'ar' ? $this->class->name_ar ?? "" : $this->class->name_en ?? "",
            'jwt_token' => $this->jwt_token,

        ];
    }
}
