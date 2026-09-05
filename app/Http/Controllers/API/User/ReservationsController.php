<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\UserApp\ConfirmReservation;
use App\Http\Resources\UserApp\ConfirmReservationResource;
use App\Models\Clinic;
use App\Models\ClinicContract;
use App\Models\Notifications;
use App\Models\Reservations;
use App\Repositories\App\UserRepository;
use App\Services\UltraMsgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReservationsController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $query = Reservations::where('parent_id', $check_authorization->id)
            ->select('id', 'booking_number', 'reception_id', 'doctor_id', 'user_id', 'clinic_id', 'date', 'appointment', 'status_id', 'booking_flow', 'payment_method', 'payment_status', 'payment_reference', 'platform_commission_rate', 'platform_commission_amount', 'clinic_net_amount', 'settlement_direction')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $reservations_list = ConfirmReservationResource::collection($query)->response()->getData();
        return $this->success(trans('user.reservations.all'), $reservations_list);
    }

    public function confirm_reservation(ConfirmReservation $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $patient_id = $request->user_id ?? $check_authorization->id;
        $use_service_one_without_package = Reservations::where('parent_id', $check_authorization->id)->exists();
//        if ($use_service_one_without_package && $check_authorization->package_id == 4) {
//            return $this->respondWithError(trans('user.doctors.check_reservation_one'));
//        }

        $doctor = Clinic::where('id', $request->id)
            ->select('id', 'parent_id', 'name', 'app_type')
            ->first();
        if (!$doctor) {
            return $this->respondNotFound(trans('messages.something_went_wrong'));
        }

        $clinic = Clinic::where('id', $doctor->parent_id)->first();
        if (!$clinic) {
            return $this->respondNotFound(trans('messages.something_went_wrong'));
        }

        $bookingPolicy = $doctor->bookingPolicy();
        $isInstantBooking = $bookingPolicy['booking_flow'] === 'instant';
        $requiresOnlinePayment = $bookingPolicy['payment_method'] === 'online';

        if ($isInstantBooking) {
            $rules = [
                'appointment_time' => ['required', 'string'],
                'date' => ['required', 'date_format:Y-m-d'],
            ];

            if ($requiresOnlinePayment) {
                $rules['payment_reference'] = ['required', 'string'];
            }

            $validator = Validator::make($request->all(), $rules, [
                'appointment_time.required' => trans('user.doctors.appointment_time'),
                'date.required' => trans('messages.auth.date_created'),
                'payment_reference.required' => 'Payment reference is required before confirming this booking.',
            ]);

            if ($validator->fails()) {
                return $this->throwValidation($validator->errors()->first());
            }
        }

        $check_reservation_exist = Reservations::where('user_id', $patient_id)
            ->where('doctor_id', $request->id)
            ->whereIn('status_id', [1, 2])
            ->where(function ($query) {
                $query->whereNull('date')->orWhere('date', '>=', date('Y-m-d'));
            })
            ->first();
        $another_reservation_exist = Reservations::where('user_id', $patient_id)
            ->where('doctor_id', $request->id)
            ->where('date', '<=', date('Y-m-d'))
            ->where('status_id', 1)
            ->get();

        if ($check_reservation_exist) {
            return $this->respondWithError(trans('user.doctors.reservation_exist'));
        }

        if ($another_reservation_exist && count($another_reservation_exist) >= 2) {
            return $this->respondWithError(trans('user.doctors.another_reservation_exist'));
        }

        $reception = Clinic::where('parent_id', $clinic->id)->where('app_type', 2)->select('id', 'parent_id')->first();
        $price = (float) ($request->price ?? 0);
        $commissionRate = (float) ($bookingPolicy['commission_rate'] ?? 0);
        $commissionAmount = in_array($bookingPolicy['contract_model'], [ClinicContract::ONLINE_PAYMENT_COMMISSION, ClinicContract::CASH_COMMISSION], true)
            ? round($price * ($commissionRate / 100), 2)
            : 0;

        $data = $request->all();
        $data['booking_number'] = rand(111111111, 999999999);
        $data['clinic_id'] = $clinic->id;
        $data['reception_id'] = $reception->id ?? null;
        $data['doctor_id'] = $request->id;
        $data['parent_id'] = $check_authorization->id;
        $data['user_id'] = $patient_id;
        $data['date'] = $isInstantBooking ? $request->date : null;
        $data['appointment'] = $isInstantBooking ? $request->appointment_time : null;
        $data['payment_status'] = 1;
        $data['status_id'] = $isInstantBooking ? 2 : 1;
        $data['booking_flow'] = $bookingPolicy['booking_flow'];
        $data['payment_method'] = $bookingPolicy['payment_method'];
        $data['platform_commission_rate'] = $commissionRate;
        $data['platform_commission_amount'] = $commissionAmount;
        $data['clinic_net_amount'] = $bookingPolicy['payment_method'] === 'online' ? max($price - $commissionAmount, 0) : $price;
        $data['settlement_direction'] = $this->settlementDirection($bookingPolicy['contract_model']);
        $data['financial_cycle_date'] = now()->startOfMonth()->toDateString();

        $create_reservation = Reservations::create($data);
        Reservations::generate_qrCode($create_reservation->booking_number);

        Notifications::createForAdminPanel($create_reservation->clinic_id, [
            'title_ar' => $isInstantBooking ? 'حجز مؤكد جديد' : 'طلب حجز معلق',
            'title_en' => $isInstantBooking ? 'New confirmed booking' : 'New unscheduled booking request',
            'message_ar' => $isInstantBooking
                ? 'يوجد حجز مؤكد جديد رقم ' . $create_reservation->booking_number . '.'
                : 'يرغب المريض: ' . $check_authorization->name . ' في حجز موعد مع الطبيب: ' . $doctor->name . '. يرجى التواصل مع المريض والتأكيد.',
            'message_en' => $isInstantBooking
                ? 'New confirmed booking #' . $create_reservation->booking_number . '.'
                : 'Patient ' . $check_authorization->name . ' wants to book with doctor ' . $doctor->name . '. Please contact the patient and confirm.',
        ], $create_reservation->reception_id);

        if (!$isInstantBooking) {
            $this->sendUnscheduledBookingWhatsapp($clinic, $doctor, $check_authorization);
        }

        return $this->success(
            $isInstantBooking ? trans('user.doctors.send_reservation') : $bookingPolicy['booking_message'],
            [
                'reservation_id' => $create_reservation->id,
                'booking_flow' => $create_reservation->booking_flow,
                'status_id' => $create_reservation->status_id,
                'payment_method' => $create_reservation->payment_method,
                'payment_status' => (int) $create_reservation->payment_status,
                'platform_commission_rate' => (float) $create_reservation->platform_commission_rate,
                'platform_commission_amount' => (float) $create_reservation->platform_commission_amount,
                'clinic_net_amount' => (float) $create_reservation->clinic_net_amount,
                'settlement_direction' => $create_reservation->settlement_direction,
                'message' => $isInstantBooking ? '' : $bookingPolicy['booking_message'],
            ]
        );
    }

    public function cancel_reservation(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $cancel_reservation = Reservations::where('id', $request->id)->first();
        $cancel_reservation->status_id = $request->status_id;
        $cancel_reservation->save();

        return $this->respondWithMessage(trans('user.doctors.cancel_reservation'));
    }

    private function settlementDirection($contractModel)
    {
        if ($contractModel === ClinicContract::ONLINE_PAYMENT_COMMISSION) {
            return 'platform_pays_clinic';
        }

        if ($contractModel === ClinicContract::CASH_COMMISSION) {
            return 'clinic_pays_platform';
        }

        return 'annual_subscription_only';
    }

    private function sendUnscheduledBookingWhatsapp(Clinic $clinic, Clinic $doctor, $patient)
    {
        $phone = $clinic->communication_officer_phone ?: $clinic->phone;

        if (!$phone) {
            return;
        }

        $message = 'يرغب المريض: ' . $patient->name . ' في حجز موعد مع الطبيب: ' . $doctor->name . '. يرجى التواصل مع المريض والتأكيد.';

        try {
            app(UltraMsgService::class)->sendMessage($phone, $message);
        } catch (\Throwable $exception) {
            Log::warning('Unable to send unscheduled booking WhatsApp notification.', [
                'clinic_id' => $clinic->id,
                'doctor_id' => $doctor->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
