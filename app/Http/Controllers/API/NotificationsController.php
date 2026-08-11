<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\NotificationsResource;
use App\Models\Notifications;
use App\Models\User;
use App\Repositories\App\MainRepository;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class NotificationsController extends APIController
{
    public $repository;
    public $user_repository;

    function __construct(Request $request, MainRepository $repository, UserRepository $user_repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
        $this->user_repository = $user_repository;
    }


    //  get notifications list
    function index(Request $request)
    {
        if ($request->type == 0) {   // user cycle
            $check_authorization = $this->user_repository->checkJwtAuth($request);
            $receiver_id = 'user_id';
        } else {
            $check_authorization = $this->repository->checkJwtAuth($request);
            $receiver_id = 'clinic_id';
        }
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $query = Notifications::where($receiver_id, $check_authorization->id)->orwhere('type', 0)->orderBy('id', 'desc')->select('id', 'title_' . $this->lang . ' as title', 'message_' . $this->lang . ' as message', 'flag', 'url', 'coupon_status', 'image')->paginate(20);
        $notifications_list = NotificationsResource::collection($query)->response()->getData();
        return $this->success(trans('messages.notifications.all'), $notifications_list);
    }


    public function sendNotification()
    {
        $obj = User::where('id', 2)->first();
        $app_key = env('PUSHER_APP_KEY');
        $app_secret = env('PUSHER_APP_SECRET');
        $app_id = env('PUSHER_APP_ID');
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ];

        $pusher = new \Pusher\Pusher($app_key, $app_secret, $app_id, $options);
        $pusher->trigger('channel_' . $obj->type . '_' . $obj->type_id, 'notification', $obj);
        return response()->json(["success" => "success"]);
    }
}
