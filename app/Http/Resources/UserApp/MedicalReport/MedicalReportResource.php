<?php

namespace App\Http\Resources\UserApp\MedicalReport;

use App\Models\MedicalReport;
use App\Models\PatientMedicalReport;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $authorization = request()->header('Authorization');
        $user = User::checkJwtAuth($authorization);
        $user_id = $request->user_id ?? $user->id;

        if ($this->type == 1) {
            return [
                'id' => $this->id,
                'question' => $this->question,
                'type' => $this->type,
                'diseases' => MedicalReport::diseases_list($this->diseases, $request->header('lang'), $user_id),
            ];
        } else {
            $is_selected = PatientMedicalReport::where('user_id', $user_id)->where('report_id', $this->id)->orderBy('id','desc')->first();

            $reasons = $is_selected ? $is_selected->reason : "";
            $answer_flag = $is_selected ? $is_selected->answer_flag : "";
            return [
                'id' => $this->id,
                'question' => $this->question,
                'type' => $this->type,
                'reason' => $this->type != 1 ? $reasons : '',
                'answer_flag' => $this->type != 1 ? $answer_flag : '',
            ];
        }


    }
}
