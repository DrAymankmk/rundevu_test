<?php

namespace App\Http\Resources\UserApp\Invoices;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceDetailsResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'patient_name' => $this->user->name ?? "",
            'patient_type' => !empty($this->user->company_id) ? trans('admin.insurance') : trans('admin.cash'),
            'clinic_name' => $this->doctor->owner->name,
            'clinic_image' => $this->doctor->owner->image,
            'doctor_name' => $this->doctor->name ?? "",
            'vat_reg_no' => 541515153313515,
            'file_number' => $this->user->file_number ?? "",
            'pat_ID' => $this->user->ID_Number ?? "",
            'Policy' => $this->user->bill_number ?? null,
            'user_id' => $this->user->id,
            'phone_number' => $this->user->phone ?? null,
            'insurance_Co' => app()->getLocale() == 'en' ? $this->user->company->name_en : $this->user->company->name_ar ?? "",
            'nationality' => app()->getLocale() == 'en' ? $this->user->nationality->name_en : $this->user->nationality->name_ar ?? "",
            'payment_status' => $this->payment_status,
            'date' => date('Y-m-d', strtotime($this->created_at)),
            'services' => $this->services->map(function ($service) {
                return [
                    'code' => $service->services->code ?? "",
                    'qty' => $service->qty,
                    'price' => $service->price,
                    'discount' => $service->discount ?? 0,
                    'tax' => $service->tax  ?? 0,
                    'total' => ($service->price ?? 0 - $service->discount ?? 0),
                ];
            }),
            'total_before_discount'=> $this->services->sum('price') ?? 0,
            'discount'=> $this->services->sum('discount'),
            'total_after_discount'=> $this->services->sum('price') ?? 0 -  $this->services->sum('discount') ?? 0,
            'Total_deductible'=> $this->total_price * ((100-$this->company_total_deductible) / 100) ?? 0,
            'Items_with_15%_VAT'=> $this->patient_tax ?? 0,
            'Items_with_0%_VAT'=> $this->patient_tax ?? 0,
            'company_VAT'=> $this->company_tax ?? 0,
            'patient_VAT'=> $this->patient_tax  ?? 0,
            'company_Tot_VAT'=> $this->total_price * ($this->company_total_deductible / 100) ?? 0,
            'total_amount_paid'=> $this->total_amount_paid,
            'remaining_amount'=> $this->total_price ?? 0 - $this->total_amount_paid ?? 0,
            'notes'=> $this->other_info ?? "",
            'qr_code'=> "",
        ];
    }
}
