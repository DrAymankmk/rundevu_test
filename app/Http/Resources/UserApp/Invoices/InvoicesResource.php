<?php

namespace App\Http\Resources\UserApp\Invoices;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoicesResource extends JsonResource
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
            'invoice_number'            => $this->invoice_number,
            'patient_name'            => $this->user->name ?? "",
            'patient_type'          => !empty($this->user->company_id) ?  trans('admin.insurance') : trans('admin.cash'),
            'clinic_name'          => $this->doctor->owner->name,
            'clinic_image'          => $this->doctor->owner->image,
            'doctor_name'          => $this->doctor->name ?? "",
            'total_price'          => (string)$this->total_price ?? "0" ,
            'Indebtedness'          => (string)($this->total_price - $this->total_amount_paid ?? "0") ,
            'payment_status'          => $this->payment_status ,
            'date'       => date('Y-m-d',strtotime($this->created_at)),
        ];
    }
}
