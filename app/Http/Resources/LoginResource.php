<?php

namespace App\Http\Resources;

use App\Models\Notifications;
use App\Models\ShiftEmployee;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'department_id'            => (int)$this->app_type,
            'name'          => $this->name,
            'phone'      => $this->phone,
            'email'      => $this->email,
            'image'      => $this->image,
            'jwt_token'     => $this->jwt_token,
            'notifications_count'     => Notifications::where('clinic_id', $this->id)->count(),
            'gender'     => (int)$this->gender,
            'check_in'     => ShiftEmployee::where(['employee_id'=>$this->id,'dateA'=>date('Y-m-d'),'status'=>1])->where('check_in','!=',null)->exists(),
            'check_out'     => ShiftEmployee::where(['employee_id'=>$this->id,'dateA'=>date('Y-m-d'),'status'=>1])->where('check_out','!=',null)->exists(),
        ];
    }
}
