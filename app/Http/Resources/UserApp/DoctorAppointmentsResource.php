<?php

namespace App\Http\Resources\UserApp;

use App\Models\ClinicOffer;
use App\Models\DoctorAppointments;
use App\Models\DoctorCondition;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorAppointmentsResource extends JsonResource
{
    public function toArray($request)
    {
        $get_numbers_patient = DoctorCondition::where('doctor_id', $request->id)->first();
        if ($get_numbers_patient) {
            $period = $get_numbers_patient->number_patients;
        } else {
            $period = 30;
        }
        $doctor_appointments = DoctorAppointments::SplitTime($request, $this->time_from, $this->time_to, $period);
        return [
            'data' => !empty($doctor_appointments) ? $doctor_appointments : []
        ];
    }
}
