<?php

namespace App\Http\Resources;

use App\Models\AllPermissions;
use App\Models\ClinicsPermissions;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorPermissionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'permission' => $this->permissions->permission,

        ];
    }
}
