<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionsEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'dateA' => $this->dateA,
            'permission_type' => $this->permissions_type->name_en,
            'reason' => $this->reason,
            'status' => $this->status,
        ];
    }
}
