<?php

namespace App\Http\Requests\Admin\Nursing;

use App\Http\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class EmergencyRequest extends AdminRequest
{

          public function authorize()
          {
                    return true;
          }

          public function rules()
          {
                    return [
                              'user_id' => 'required',
                              'heat' => 'required',
                              'pulse' => 'required',
                              'blood_pressure' => 'required',
                              'pain_rate' => 'required',
                              'weight' => 'required',
                              'height' => 'required',
                              'breathing' => 'required',
                              'oxygen_ratio' => 'required',
                              'body_mass_rate' => 'required',
                              'FBS' => 'required',
                              'RBS' => 'required',
                    ];
          }

          function messages()
          {
                    return [
                              'user_id.required' => trans('admin.user_id'),
                              'heat.required' => trans('admin.heat_r'),
                              'pulse.required'=> trans('admin.pulse_r'),
                              'blood_pressure.required'=> trans('admin.blood_pressure_r'),
                              'pain_rate.required'=> trans('admin.pain_rate_r'),
                              'weight.required'=> trans('admin.weight_r'),
                              'height.required'=> trans('admin.height_r'),
                              'breathing.required'=> trans('admin.breathing_r'),
                              'oxygen_ratio.required'=> trans('admin.oxygen_ratio_r'),
                              'body_mass_rate.required'=> trans('admin.body_mass_rate_r'),
                              'FBS.required'=> trans('admin.FBS_r'),
                              'RBS.required'=> trans('admin.RBS_r'),
                    ];
          }
}
