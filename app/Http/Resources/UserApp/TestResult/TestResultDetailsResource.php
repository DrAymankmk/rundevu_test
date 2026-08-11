<?php

namespace App\Http\Resources\UserApp\TestResult;

use App\Models\PatientService;
use App\Models\TestResultDetails;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResultDetailsResource extends JsonResource
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
            'name' => $this->user->name,
            'image' => $this->user->image,
            'gender' => $this->user->gender,
            'ID_Number' => $this->user->ID_Number,
            'date' => $this->user->dob,
            'file_number' => $this->user->file_number ?? "",
            'expired_date' => $this->user->expired_date,
            'phone' => $this->user->phone,
            'qr_code_user' => asset('media/users/qr_code/' . $this->user->ID_Number . '.' . 'png'),
//            'report' => $this->report_details,
            'ray_result' => $this->lab_result ?? "",
            'rays_images' => TestResultDetails::where('service_id', $this->id)->select('id', 'images')->get(),
            'service_name' => $request->header('lang') == 'ar' ? $this->services->name_ar : $this->services->name_en,
            'trans_date' => date('Y-m-d H:i', strtotime($this->created_at)),
            'sampling_date' => date('Y-m-d H:i', strtotime($this->created_at)),
            'result_date' => date('Y-m-d H:i', strtotime($this->created_at)),
            'lab_result' => PatientService::lab_result($this, $this->user_id, $request->header('lang')),
        ];
    }
}
