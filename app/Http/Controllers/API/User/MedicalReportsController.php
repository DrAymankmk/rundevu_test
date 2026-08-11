<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\UserApp\MedicalReport\MedicalReportResource;
use App\Models\MedicalReport;
use App\Models\PatientMedicalReport;

use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class MedicalReportsController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    // medical reports
    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $query = MedicalReport::select('id', 'question_' . $this->lang . ' as question', 'type')->where('parent_id', null)->paginate(100);
        $medical_report_list = MedicalReportResource::collection($query)->response()->getData();
        return $this->success(trans('user.data'), $medical_report_list);
    }


    // add report
    public function add_report(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $data = $request->all();

        if ($request->answer_id) {
            $i = 0;
            $create_report = new PatientMedicalReport();
            $create_report->user_id = $request->user_id ?? $check_authorization->id;
            $create_report->save();
            foreach ($request->answer_id as $answer) {
                $medical_report = MedicalReport::where('id', $answer)->first();

                $data['user_id'] = $request->user_id ?? $check_authorization->id;
                $data['parent_id'] = $create_report->id ?? null;

                $data['answer_id'] = $answer ?? null;
                $data['report_id'] = $medical_report->id ?? null;
                $data['report_type'] = $medical_report->type ?? null;
                $data['answer_flag'] = $request->answer_flag[$i];
                $data['reason'] = !empty($request->reason) ? $request->reason[$i] : "";
                $add_or_update_report = PatientMedicalReport::create($data);
//                $add_or_update_report = PatientMedicalReport::Create(
//                    [
////                        'user_id' => $request->user_id ?? $check_authorization->id,
////                        'answer_id' => $answer ?? null,
////                        'report_id' => $medical_report->id ?? null,
////                        'report_type' => $medical_report->type ?? null,
//                        'parent_id' => $create_report->id ?? null
//                    ],
//                    [
//                        'user_id' => $request->user_id ?? $check_authorization->id,
//                        'parent_id' => $create_report->id ?? null,
//                        'answer_id' => $answer ?? null,
//                        'report_id' => $medical_report->id ?? null,
//                        'report_type' => $medical_report->type ?? null,
//                        'answer_flag' => $request->answer_flag[$i],
//                        'reason' => !empty($request->reason) ? $request->reason[$i] : "",
//                    ]
//                );
                $i++;
            }
        }

        if ($add_or_update_report) {
            return $this->respondWithMessage(trans('messages.Added'));
        } else {
            $this->respondWithError(trans('messages.something_went_wrong'));
        }
    }
}
