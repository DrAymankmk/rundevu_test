<?php

namespace App\Events;
use App\Models\ReservationChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
class ReservationChatMessageEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public function __construct(ReservationChat $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return [
            new Channel('reservation.' . $this->message->reservation_id),
            new Channel('user.' . $this->message->user_id),
            new Channel('admin-notifications'),
        ];
    }

    public function broadcastAs()
    {
        return 'new-message';
    }
}
