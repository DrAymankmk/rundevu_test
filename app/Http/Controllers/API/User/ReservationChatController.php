<?php

namespace App\Http\Controllers\API\User;

use App\Events\ReservationChatMessageEvent;
use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserApp\CreateReservationChatRequest;
use App\Http\Requests\UserApp\ReservationRateRequest;
use App\Http\Resources\UserApp\ConfirmReservationResource;
use App\Http\Resources\UserApp\ReservationChatResource;
use App\Models\ReservationChat;
use App\Models\Notifications;
use App\Models\ReservationRate;
use App\Models\Reservations;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationChatController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }


    // reservations
    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
//        $query = ReservationChat::where('reservation_id',$request->reservation_id)->groupBy(DB::raw('Date(created_at)'))->paginate(20);

        $query = ReservationChat::where('reservation_id',$request->reservation_id)->groupBy(DB::raw('Date(created_at)'))
            ->orderBy('created_at', 'DESC')->paginate(10);

        $reservations_chat_list = ReservationChatResource::collection($query)->response()->getData();
        return $this->success(trans('user.chat.details'), $reservations_chat_list);
    }


    // create chat
    function create(CreateReservationChatRequest $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $reservation = Reservations::where('id', $request->reservation_id)->select('id', 'reception_id','clinic_id','user_id')->first();
        $data = $request->all();
        if ($request->flag_type == 2) {
            $data['user_id'] = $reservation->user_id;
             $data['sender_id'] = $reservation->clinic_id;
            $data['sender_type'] = 2;
//            $data['receiver_id'] = $reservation->user_id;
        } else {
            $data['user_id'] = $check_authorization->id;
//        $data['reception_id'] = $reservation->reception_id;
            $data['sender_type'] = 1;
            $data['receiver_id'] = $reservation->clinic_id;
        }

        $last_message = ReservationChat::create($data);
        if ($last_message) {
            $adminMessage = trim((string) $last_message->message);
            $adminMessageEn = $adminMessage;
            if ($adminMessage === '') {
                $adminMessage = ($last_message->file || $last_message->record)
                    ? 'تم إرسال مرفق جديد في شات الحجز.'
                    : 'لديك رسالة جديدة في شات الحجز.';
                $adminMessageEn = ($last_message->file || $last_message->record)
                    ? 'A new attachment has been sent in booking chat.'
                    : 'You have a new booking message.';
            }

            if ($request->flag_type == 2) {
                Notifications::create([
                    'clinic_id' => $reservation->clinic_id,
                    'user_id' => $reservation->user_id,
                    'receiver_id' => $reservation->user_id,
                    'type' => 1,
                    'app_type' => 1,
                    'title_ar' => 'New message',
                    'title_en' => 'New message',
                    'message_ar' => 'You have a new booking message.',
                    'message_en' => 'You have a new booking message.',
                ]);
            } else {
                Notifications::createForAdminPanel($reservation->clinic_id, [
                    'title_ar' => 'رسالة جديدة',
                    'title_en' => 'New message',
                    'message_ar' => $adminMessage,
                    'message_en' => $adminMessageEn,
                ]);
            }
            broadcast(new ReservationChatMessageEvent($last_message))->toOthers();
            return $this->respondWithMessage(trans('user.chat.send_message'));
        } else {
            return $this->respondWithError(trans('messages.something_went_wrong'));
        }
    }




    public function rate(ReservationRateRequest $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $reservation = Reservations::where('id', $request->reservation_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$reservation) {
            return response()->json([
                'status'  => 201,
                'message' => trans('messages.reservation.not_found'),
            ], 201);
        }

        $alreadyRated = ReservationRate::where('reservation_id', $reservation->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyRated) {
            return response()->json([
                'status'  => 201,
                'message' => trans('messages.reservation.already_rated'),
            ], 201);
        }

        // ✅ إنشاء التقييم
        $review = ReservationRate::create([
            'clinic_id'      => $reservation->clinic_id,
            'doctor_id'      => $reservation->doctor_id,
            'reservation_id' => $reservation->id,
            'user_id'        => $user->id,
            'comment'        => $request->comment,
            'rate_value'     => $request->rate_value,
        ]);

        return response()->json([
            'status'  => 200,
            'message' => trans('messages.reservation.rate_success'),
            'data'    => $review,
        ], 200);
    }

}
