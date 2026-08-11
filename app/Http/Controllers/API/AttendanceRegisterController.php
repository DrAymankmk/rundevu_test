<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use App\Models\AttendanceSetting;
use App\Models\Shift;
use App\Models\ShiftEmployee;
use App\Repositories\App\MainRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceRegisterController extends APIController
{
    public $repository;

    function __construct(Request $request, MainRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    // attendance register
    function attendance_register(Request $request)
    {
        $currentTimeInAnotherTimezone = Carbon::now('Asia/Riyadh');

        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $check_attendance = ShiftEmployee::where(['employee_id' => $check_authorization->id, 'dateA' => date('Y-m-d'), 'status' => 1])->first();
        if ($check_attendance) {

            $currentDateTime = Carbon::now();

            $check_shift = Shift::whereId($check_attendance->shift_id)->first();

            $endTime = $currentDateTime;
//            $endTime = $currentDateTime->addHours(1);

            $start = Carbon::parse($check_shift->time_from);

            // Calculate the time difference in minutes
            $timeDifferenceInMinutes = $endTime->diffInMinutes($start);

            $check_attendance_setting = AttendanceSetting::where('clinic_id', $check_authorization->parent_id)->first();


            if (empty($check_attendance->check_in)) {

                if ($endTime->lessThan($check_shift->time_from)) {
                    return $this->respondWithError(trans('messages.attendance.attendance_work') . $check_shift->time_from);
                }
                $timestamp = Carbon::parse($check_shift->time_from);
                $check_attendance_time = $check_attendance_setting->attendance_period ?? 4;
                $newTimestamp = $timestamp->addHours($check_attendance_time);
                if ($check_attendance_setting) {
                    if ($endTime->greaterThan($newTimestamp)) {
                        return $this->respondWithError(trans('messages.attendance.check_attendance_time'));
                    }
                }
                $check_attendance->check_in = $endTime;
                if ($timeDifferenceInMinutes > $check_shift->minute_allow_delay) {
                    $check_attendance->total_delay_minute = $timeDifferenceInMinutes;
                }
                $message = trans('messages.attendance.check_in');
            } else {

// Calculate a new timestamp that is 4 hours later
                if ($check_attendance_setting) {
                    // Retrieve the timestamp from the database or any other source
                    $timestamp = Carbon::parse($check_shift->time_from); // Replace $record->created_at with your actual timestamp

                    $check_attendance_period = $check_attendance_setting->attendance_period ?? 0;
                    $check_leaving_period = $check_attendance_setting->leaving_period ?? 0;
                    $newTimestamp = $timestamp->addHours($check_attendance_period);
                } else {
                    $newTimestamp = Carbon::parse($check_shift->time_to);
                }
                if ((!empty($check_attendance->check_in)) && ($endTime->lessThan($newTimestamp))) {
                    return $this->respondWithError(trans('messages.attendance.attendance_already'));
                }
                $check_attendance->check_out = $endTime;
                $message = trans('messages.attendance.check_out');
            }
            if ($request->flag == 1) {
                $check_attendance->check_in = null;
                $check_attendance->check_out = null;
            }
            $check_attendance->save();
        } else {
            return $this->respondWithError(trans('messages.attendance.no_work'));
        }
        return $this->success($message, new LoginResource($check_authorization));
    }
}
