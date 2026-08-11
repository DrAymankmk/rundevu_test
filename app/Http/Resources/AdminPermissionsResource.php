<?php

namespace App\Http\Resources;

use App\Models\AllPermissions;
use App\Models\ClinicsPermissions;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPermissionsResource extends JsonResource
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
            'name' => $this->name,
            'flag' => (int)$this->flag,
            'permission' => $this->permission,
            'permissions_type' => AllPermissions::parent($request->employee_id, $this->id, $request->header('lang')),

        ];
    }
}
