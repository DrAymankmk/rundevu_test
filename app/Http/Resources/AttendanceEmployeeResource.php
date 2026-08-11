<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status == 0) {
            $audience = trans('messages.attendance.official_vacation');
            $leave = trans('messages.attendance.official_vacation');
        } elseif (empty($this->check_in) && empty($this->check_out)) {
            $audience = trans('messages.attendance.absence');
            $leave = trans('messages.attendance.absence');
            $audience = '-';
            $leave = '-';
        } else {
            $audience = !empty($this->check_in) ? date('h:i', strtotime($this->check_in)) : "";
            $leave = !empty($this->check_out) ? date('h:i', strtotime($this->check_out)) : "";
        }
        return [
            'id' => $this->id,
            'dateA' => $this->dateA,
            'day' => Carbon::createFromFormat('Y-m-d', $this->dateA)->dayName,
            'audience' => $audience,
            'leave' => $leave,
            'audience_another_employee' => !empty($this->checkin_another_employee) ? $this->audience_another_employee->name : "",
            'leave_another_employee' => !empty($this->checkout_another_employee) ? $this->leave_another_employee->name : "",
        ];
    }
}
