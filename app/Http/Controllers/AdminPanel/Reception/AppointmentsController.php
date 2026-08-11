<?php

namespace App\Http\Controllers\AdminPanel\Reception;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\Notifications;
use App\Models\Reservations;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentsController extends Controller
{
    // appointments
    public function appointments(Request $request, $patient_id = null)
    {
        $data['receptions'] = Clinic::where('parent_id', auth()->user()->parent_id)
            ->where('app_type', 2)
            ->pluck('id');

        $data['doctors'] = Clinic::where('parent_id', auth()->user()->parent_id)
            ->where('app_type', 3)
            ->get();

        $doctorIds = $data['doctors']->pluck('id');

        $data['patients'] = User::orderByDesc('id')->get();

        $query = Reservations::with(['doctor.specialties.specialties', 'user', 'reservation_status'])
            ->where(function ($q) use ($doctorIds, $data) {
                $q->whereIn('doctor_id', $doctorIds)
                    ->orWhereIn('reception_id', $data['receptions']);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('phone', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        if ($patient_id) {
            $query->where('user_id', $patient_id);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('patient_id')) {
            $query->where('user_id', $request->patient_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        } else {
            if (empty($request->patient_id)) {
                $query->whereDate('date', now()->toDateString());
            }
        }

        $data['specialists'] = Specialty::where('status', 1)
            ->select('id', 'name_' . $this->lang() . ' as name')
            ->get();

        $data['reservations'] = $query->latest()->get();
        $data['patient_id'] = $patient_id ?? null;

        return view('reception.appointments', compact('data'));
    }

    function cancel_reservation($id)
    {
        $cancel_reservation = Reservations::where('id', $id)->first();
        $cancel_reservation->reception_id = auth()->user()->id;
        $cancel_reservation->status_id = 4;
        $cancel_reservation->save();
        $this->notifyReservationUser($cancel_reservation, 'rejected');
        $message = trans('admin.cancel_reservation');
        return response()->json($message);
    }

    // waitingList_reservation

    function waitingList_reservation($id)
    {
        $waiting_list_reservation = Reservations::where('id', $id)->first();
        $check_last_waiting_list_reservation = Reservations::where('doctor_id', $waiting_list_reservation->doctor_id)
            ->whereDate('date',date('Y-m-d'))->where('id','!=',$id)->orderBy('waiting_list', 'desc')->first();
        $waiting_list_reservation->reception_id = auth()->user()->id;
        $waiting_list_reservation->waiting_list = $check_last_waiting_list_reservation ? $check_last_waiting_list_reservation->waiting_list + 1 : 1;
        $waiting_list_reservation->status_id = 1;
        $waiting_list_reservation->save();
        $this->notifyReservationUser($waiting_list_reservation, 'approved');
        $message = trans('admin.confirm_waitingList_reservation');
        return response()->json($message);
    }


    function add_appointment()
    {
        $clinic_id = auth()->user()->parent_id;
        $data['doctors'] = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 3)->get();
        $data['patients'] = User::orderBy('id', 'desc')->get();
        $data['specializations'] = ClinicSpecialist::with('specialties')->where('clinic_id', $clinic_id)->where('type', 1)->where('status', 1)->get();
        return view('reception.add_appointment', compact('data'));
    }

    public function getDoctorsFromSpecialists(Request $request)
    {
        $getDoctor_ids = ClinicSpecialist::where('specialty_id', $request->specialist_id)->where('type',1)->pluck('clinic_id');
        $getdoctors = Clinic::whereIn('id',$getDoctor_ids)->where(['parent_id'=>auth()->user()->parent_id,'app_type'=>3,'status'=>1])->get();
        return response()->json([
            'doctors_count' => count($getdoctors),
            'data' => $getdoctors,
        ]);
    }
    // getQrCodeUser
    function getQrCodeUser (Request $request)
    {
        $patient = User::with('company')->where('id',$request->patient_id)->first();
        if ($patient->expired_date <= date('Y-m-d')) {
           $status = 'invalid';
        } else {
            $status = 'valid';
        }
        return response()->json([
            'status' => $status,
            'patient' => $patient,
        ]);
    }

    // add_appointment
    function create_appointment(Request $request)
    {
        $appointmentTime = trim($request->appointment_time);

        $patient = User::where('id', $request->patient_id)
            ->select('id', 'parent_id')
            ->first();


        $exists = Reservations::where('doctor_id', $request->doctor_id)
            ->whereDate('date', $request->date)
            ->where('appointment', $appointmentTime)
            ->where('clinic_id', auth()->user()->parent_id)
            ->where(function ($query) {
                $query->whereNull('status_id')
                    ->orWhereIn('status_id', [1, 2, 3]);
            })
            ->exists();

        if ($exists) {

            session()->flash('error', 'هذا الموعد محجوز بالفعل لهذا الطبيب');
            return redirect()->back();
        }
        $data = $request->all();
        $patient = User::where('id', $request->patient_id)->select('id', 'parent_id')->first();
        $data['booking_number'] = rand(111111111, 999999999);
        $data['clinic_id'] = auth()->user()->parent_id;
        $data['reception_id'] = auth()->user()->id;
        $data['doctor_id'] = $request->doctor_id;
        $data['user_id'] = $request->patient_id;
        $data['parent_id'] = $patient->parent_id ?? $patient->id;
        $data['appointment'] = $appointmentTime;
        $data['status_id'] = 1;
        $create_reservation = Reservations::create($data);
        if ($create_reservation) {
            Reservations::generate_qrCode($create_reservation->booking_number);
            session()->flash('success', __(trans('admin.success_reservation')));
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }




    public function completeReservation($id)
    {
        try {
            $reservation = Reservations::findOrFail($id);

            // الحالات المسموح بإنهائها: 1=مؤكد, 2=في الانتظار, 3=قيد التنفيذ
            $allowedStatuses = [1];

//            if (!in_array($reservation->status_id, $allowedStatuses)) {
//                return response()->json([
//                    'message' => trans('messages.reservation.cannot_complete')
//                ], 400);
//            }

            // تحديث حالة الحجز إلى "منتهي" (افترض أن 4=منتهي)
            $reservation->update([
                'status_id' => 6,
            ]);

            if ($reservation->user) {
                app(\App\Services\LoyaltyPointsService::class)->award($reservation->user, 'completed_visit', [
                    'clinic_id' => $reservation->clinic_id,
                    'reservation_id' => $reservation->id,
                    'source_type' => Reservations::class,
                    'source_id' => $reservation->id,
                ]);
            }

            Notifications::create([
                'clinic_id' => $reservation->clinic_id,
                'user_id' => $reservation->user_id,
                'receiver_id' => $reservation->user_id,
                'type' => 1,
                'app_type' => 1,
                'title_ar' => 'Rate your booking',
                'title_en' => 'Rate your booking',
                'message_ar' => 'Booking #' . $reservation->booking_number . ' is completed. You can rate it now.',
                'message_en' => 'Booking #' . $reservation->booking_number . ' is completed. You can rate it now.',
            ]);

            return response()->json([
                'message' => trans('messages.reservation.completed_success')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error completing reservation: ' . $e->getMessage());
            return response()->json([
                'message' => trans('messages.something_went_wrong')
            ], 500);
        }
    }

    private function notifyReservationUser(Reservations $reservation, $status)
    {
        $reservation->loadMissing('user');

        $isApproved = $status === 'approved';
        $titleAr = $isApproved ? 'تم تأكيد الحجز' : 'تم رفض الحجز';
        $titleEn = $isApproved ? 'Booking approved' : 'Booking rejected';
        $messageAr = ($isApproved ? 'تم تأكيد' : 'تم رفض') . ' الحجز رقم ' . $reservation->booking_number;
        $messageEn = 'Booking #' . $reservation->booking_number . ' has been ' . ($isApproved ? 'approved.' : 'rejected. No available slots.');

        Notifications::create([
            'clinic_id' => $reservation->clinic_id,
            'user_id' => $reservation->user_id,
            'receiver_id' => $reservation->user_id,
            'type' => 1,
            'app_type' => 1,
            'title_ar' => $titleAr,
            'title_en' => $titleEn,
            'message_ar' => $messageAr,
            'message_en' => $messageEn,
        ]);

        if ($reservation->user && $reservation->user->email) {
            try {
                Mail::raw($messageEn, function ($mail) use ($reservation, $titleEn) {
                    $mail->to($reservation->user->email)->subject($titleEn);
                });
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
    }
}
