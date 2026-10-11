<?php

namespace App\Events;

use App\Models\Notifications;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Real-time notification for the user mobile app (same idea as chat).
 *
 * Channels:
 *  - user-notify.{user_id} : notification for one user
 *  - users-notify        : notification for all app users
 * Event name: notify
 */
class UserNotificationEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(Notifications $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return array_map(function ($name) {
            return new Channel($name);
        }, $this->notification->userAppBroadcastChannels());
    }

    public function broadcastAs()
    {
        return 'notify';
    }

    public function broadcastWith()
    {
        $notification = $this->notification;

        return [
            'data' => [
                'id' => $notification->id,
                'type' => (int) $notification->type,
                'app_type' => (int) $notification->app_type,
                'user_id' => $notification->user_id ?: $notification->receiver_id,
                'clinic_id' => $notification->clinic_id,
                'title_ar' => $notification->title_ar,
                'title_en' => $notification->title_en,
                'message_ar' => $notification->message_ar,
                'message_en' => $notification->message_en,
                'image' => $notification->image,
                'created_at' => optional($notification->created_at)->toDateTimeString(),
                'play_sound' => true,
                'sound' => 'default',
            ],
        ];
    }
}
