<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAppointments extends Model
{
    use HasFactory;

    static function SplitTimeOld($input, $StartTime, $EndTime, $Duration = "60")
    {

//        $ReturnArray = array();// Define output
//        $StartTime = strtotime($StartTime); //Get Timestamp
//        $EndTime = strtotime($EndTime); //Get Timestamp
//
//        $AddMins = $Duration * 60;
//
//        $startTime = Carbon::parse($StartTime);
//        $endTime = Carbon::parse($EndTime);
//
//        // Calculate the total time available
//        $totalTimeAvailable = $startTime->diffInMinutes($endTime);
//
//        // Calculate the time period for each patient
//        $timePerPatient = ($totalTimeAvailable / $Duration) * 60;
//
//        for ($i = $StartTime; $i < $EndTime;) {
////            $res_item['time'] = date("g:i", $i) . "-" . date("g:i a", $i + $AddMins);
//            $res_item['time'] = date("g:i", $i) . "-" . date("g:i a", $i + $timePerPatient);
//            $check_reservation = Reservations::where('date', $input->date)->where('appointment', $res_item['time'])->where('status_id', [1, 2])->exists();
//            $schedule_consultation_date = Reservations::where('schedule_consultation_date', $input->date)->where('schedule_consultation_time', $res_item['time'])->where('status_id', [1, 2])->exists();
//            if ($check_reservation || $schedule_consultation_date) {
//                $res_item['status'] = 2;
//            } else {
//                $res_item['status'] = 1;
//            }
//            $ReturnArray[] = $res_item;
//            //  $i += $AddMins;
//            $i += $timePerPatient;
//        }
//        return $ReturnArray;



        $ReturnArray = array(); // Define output

        $startTime = Carbon::parse($StartTime);
        $endTime = Carbon::parse($EndTime);

        $AddMins = $Duration * 60;

// Calculate the total time available
        $totalTimeAvailable = $startTime->diffInMinutes($endTime);

// Calculate the time period for each patient
        $timePerPatient = ($totalTimeAvailable / $Duration);
// Get current datetime for comparison
        $now = Carbon::now();
        for ($i = 0; $i < $Duration; $i++) {
            $intervalStart = $startTime->copy()->addMinutes($i * $timePerPatient);
            $intervalEnd = $intervalStart->copy()->addMinutes($timePerPatient);

            // ❌ Skip time slots that have already passed
            if ($intervalStart->lt($now)) {
                continue;
            }

            $res_item['time'] = $intervalStart->format("g:i") . "-" . $intervalEnd->format("g:i A");

            $check_reservation = Reservations::where('date', $input->date)
                ->where('appointment', $res_item['time'])
                ->whereIn('status_id', [1, 2])
                ->exists();

            $schedule_consultation_date = Reservations::where('schedule_consultation_date', $input->date)
                ->where('schedule_consultation_time', $res_item['time'])
                ->where('user_id', $res_item['time'])
                ->whereIn('status_id', [1, 2])
                ->exists();

            if ($check_reservation || $schedule_consultation_date) {
                $res_item['status'] = 2;
            } else {
                $res_item['status'] = 1;
            }

            $ReturnArray[] = $res_item;
        }

        return $ReturnArray;
    }

    static function SplitTime($input, $StartTime, $EndTime, $Duration = 60)
    {
        $user = null;
        $token = $input->header('Authorization');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            $user = User::where('jwt_token', $token)->select('id')->first();
        }
        $ReturnArray = [];

        $Duration = (int) $Duration;

        // ❌ حماية من القسمة على صفر
        if ($Duration <= 0) {
            return $ReturnArray;
        }

        // ✅ معالجة المواعيد التي تعبر منتصف الليل (مثل 23:00 إلى 07:00)
        $startDate = $input->date;
        $startTime = Carbon::parse($startDate . ' ' . $StartTime);

        // تحقق إذا كان وقت النهاية أقل من وقت البداية (يعبر منتصف الليل)
        $startCarbon = Carbon::parse($StartTime);
        $endCarbon = Carbon::parse($EndTime);

        if ($endCarbon->lt($startCarbon)) {
            // إضافة يوم لوقت النهاية
            $endDate = Carbon::parse($input->date)->addDay()->format('Y-m-d');
            $endTime = Carbon::parse($endDate . ' ' . $EndTime);
        } else {
            $endTime = Carbon::parse($input->date . ' ' . $EndTime);
        }

        if ($endTime->lte($startTime)) {
            return $ReturnArray;
        }

        // ✅ إجمالي الوقت بالدقائق
        $totalMinutes = $startTime->diffInMinutes($endTime);

        // ✅ عدد المواعيد الممكنة بناءً على المدة المطلوبة لكل موعد
        $numberOfSlots = floor($totalMinutes / $Duration);

        if ($numberOfSlots <= 0) {
            return $ReturnArray;
        }

        $now = Carbon::now();

        for ($i = 0; $i < $numberOfSlots; $i++) {
            $intervalStart = $startTime->copy()->addMinutes($i * $Duration);
            $intervalEnd = $intervalStart->copy()->addMinutes($Duration);

            // ❌ لا تنشئ مواعيد تتجاوز وقت النهاية
            if ($intervalEnd->gt($endTime)) {
                break;
            }

            // ❌ تجاهل المواعيد التي انقضت وقتها
            if ($intervalEnd->lte($now)) {
                continue;
            }

            // ✅ تنسيق الوقت مع مراعاة تغيير التاريخ
            $timeRange = $intervalStart->format('g:i A') . ' - ' . $intervalEnd->format('g:i A');

            // ✅ التاريخ الفعلي للموعد (للمواعيد التي تعبر منتصف الليل)
            $actualDate = $intervalStart->format('Y-m-d');
            $isReserved = Reservations::where(function ($q) use ($actualDate, $timeRange, $user, $input) {
                $q->where(function ($sub) use ($actualDate, $timeRange, $user, $input) {
                    $sub->where([
                        'date' => $actualDate,
                        'doctor_id' => $input->id ?? $input->doctor_id,
                        'appointment' => $timeRange,
                    ]);

//                    if ($user) {
//                        $sub->where('user_id', $user->id);
//                    }
                })->orWhere(function ($sub) use ($actualDate, $input) {
                    $sub->where([
                        'schedule_consultation_date' => $actualDate,
                        'doctor_id' => $input->id,
                    ]);
                });
            })
                ->where(function ($query) {
                    $query->whereNull('status_id')
                        ->orWhereIn('status_id', [1, 2, 3]);
                })
                ->exists();

            $ReturnArray[] = [
                'date' => $actualDate, // ✅ التاريخ الفعلي
                'time' => $timeRange,
                'start_time' => $intervalStart->format('H:i'),
                'end_time' => $intervalEnd->format('H:i'),
                'status' => $isReserved ? 2 : 1, // 1 = available, 2 = booked
            ];
        }

        return $ReturnArray;
    }


}
