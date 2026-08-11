<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeShiftResource extends JsonResource
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
            'name'      => !empty($this->shift_id) ? $this->shift->name : "",
            'dateA'      => $this->dateA,
            'time_from'      => !empty($this->shift->time_from) ? date('H:i a',strtotime($this->shift->time_from)) :"-",
            'time_to'      => !empty($this->shift->time_to) ? date('H:i a',strtotime($this->shift->time_to)) :"-",
            'status'      => (int)$this->status,
        ];
    }
}
