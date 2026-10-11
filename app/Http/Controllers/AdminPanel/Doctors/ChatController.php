<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Events\ReservationChatMessageEvent;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\ReservationChat;
use App\Models\Reservations;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    // chat
    public function index()
    {
        $authUser = Auth::user();
        $clinicId = $authUser->parent_id ?: $authUser->id;
        $reservationIds = Reservations::where('clinic_id', $clinicId)->pluck('id');

        $data['chat_list'] = ReservationChat::with(['reservation.user'])
            ->whereIn('id', function ($query) use ($reservationIds) {
                $query->selectRaw('MAX(id)')
                    ->from('reservation_chats')
                    ->whereIn('reservation_id', $reservationIds)
                    ->groupBy('reservation_id');
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($chat) {
                $patient = optional($chat->reservation)->user;

                if ($patient) {
                    $chat->receiver_id = $patient->id;
                    $chat->setRelation('receiver', $patient);
                }

                return $chat;
            });

        return view('doctors.chat.index', compact('data'));
    }

    public function loadChatMessages(Request $request)
    {
        $request->validate([
            'receiver_id' => 'nullable|exists:users,id',
            'reservation_id' => 'required|exists:reservations,id',
            'last_id' => 'nullable|integer|min:0',
        ]);

        $authUser = auth()->user();
        $clinicId = $authUser->parent_id ?: $authUser->id;
        $reservation = Reservations::with('user')
            ->where('clinic_id', $clinicId)
            ->findOrFail($request->reservation_id);

        $receiverId = (int) optional($reservation->user)->id;
        $reservationId = $reservation->id;
        $lastId = (int) $request->input('last_id', 0);
        $authId = $authUser->id;

        $chatMessages = ReservationChat::with(['sender', 'receiver'])
            ->where('reservation_id', $reservationId)
            ->when($lastId > 0, function ($q) use ($lastId) {
                $q->where('id', '>', $lastId);
            })
            ->orderBy('id', 'asc')
            ->get();

        ReservationChat::where('reservation_id', $reservationId)
            ->where('sender_id', $receiverId)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'chatMessages' => $chatMessages,
            'sender_id' => $authId,
            'receiver_id' => $receiverId,
        ]);
    }

    public function loadChat(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'receiver_id' => 'nullable|exists:users,id',
        ]);

        $currentUser = auth()->user();
        $clinicId = $currentUser->parent_id ?: $currentUser->id;
        $reservation = Reservations::where('clinic_id', $clinicId)
            ->findOrFail($request->reservation_id);

        $messages = ReservationChat::where('reservation_id', $reservation->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        $receiverId = optional($reservation->user)->id;

        return response()->json([
            'success' => true,
            'chatMessages' => $messages,
            'sender_id' => $currentUser->id,
            'receiver_id' => $receiverId,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'nullable|string',
            'receiver_id' => 'required|exists:users,id',
            'reservation_id' => 'required|exists:reservations,id',
            'file' => 'nullable|file|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        if (!$request->message && !$request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'errors' => ['Message or file is required'],
            ], 422);
        }

        $reservation = Reservations::whereId($request->reservation_id)->first();
        $receiverId = (int) $reservation->user_id;

        $data = [
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'reservation_id' => $request->reservation_id,
            'message' => $request->message,
            'user_id' => $receiverId,
            'sender_type' => 2,
            'receiver_type' => 1,
            'is_read' => false,
        ];

        if ($request->hasFile('file')) {
            $path = $request->file('file');
            $data['file'] = $path;
        }

        $chat = ReservationChat::create($data);
        $chat->load(['sender', 'receiver']);

        $notification = $this->reservationChatNotificationText($reservation, $chat);

        $receiver = User::select('id', 'platform', 'firebase_token')->find($receiverId);
        Log::channel('push')->info('Reception chat message to user: sending push notification.', [
            'chat_id' => $chat->id,
            'reservation_id' => $reservation->id,
            'clinic_id' => $reservation->clinic_id,
            'sender_id' => auth()->id(),
            'user_id' => $receiverId,
            'user_platform' => optional($receiver)->platform,
            'user_has_token' => filled(optional($receiver)->firebase_token),
            'has_file' => $request->hasFile('file'),
        ]);

        Notifications::create([
            'clinic_id' => $reservation->clinic_id,
            'user_id' => $receiverId,
            'receiver_id' => $receiverId,
            'type' => 1,
            'app_type' => 1,
            'title_ar' => $notification['title_ar'],
            'title_en' => $notification['title_en'],
            'message_ar' => $notification['message_ar'],
            'message_en' => $notification['message_en'],
        ]);

        broadcast(new ReservationChatMessageEvent($chat))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $chat
        ]);
    }

    private function reservationChatNotificationText(Reservations $reservation, ReservationChat $message): array
    {
        $bookingNumber = $reservation->booking_number ?: $reservation->id;
        $messageText = trim((string) $message->message);

        if ($messageText !== '') {
            $messagePreview = Str::limit($messageText, 80);
        } elseif ($message->file || $message->record) {
            $messagePreview = 'تم إرسال مرفق جديد في شات الحجز.';
        } else {
            $messagePreview = 'لديك رسالة جديدة في شات الحجز.';
        }

        return [
            'title_ar' => 'رسالة جديدة من حجز رقم ' . $bookingNumber,
            'title_en' => 'New message from booking #' . $bookingNumber,
            'message_ar' => $messagePreview,
            'message_en' => $messagePreview,
        ];
    }

    public function bookingChat($bookingId)
    {
        $booking = Reservations::with('reception', 'user', 'reservation_status')->findOrFail($bookingId);
        $currentUser = auth()->user();
        $otherParty = ($currentUser->app_type == 2) ? $booking->reception : $booking->user;

        $messages = ReservationChat::where('reservation_id', $bookingId)
            ->where(function ($query) use ($currentUser) {
                $query->where('sender_id', $currentUser->id)
                    ->orWhere('receiver_id', $currentUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        ReservationChat::where('receiver_id', $currentUser->id)
            ->where('reservation_id', $bookingId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $unreadCount = ReservationChat::where('receiver_id', auth()->id())
            ->where('sender_id', $booking->sender_id)
            ->where('is_read', false)
            ->count();

        return view('doctors.chat.booking', [
            'booking' => $booking,
            'messages' => $messages,
            'otherParty' => $otherParty,
            'currentUser' => $currentUser,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        $currentUser = Auth::user();

        $query = ReservationChat::where('receiver_id', $currentUser->id)
            ->where('sender_id', $request->sender_id)
            ->where('is_read', false);

        if ($request->has('reservation_id') && $request->reservation_id) {
            $query->where('reservation_id', $request->reservation_id);
        }

        $updated = $query->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
            'updated_count' => $updated
        ]);
    }

    public function chatList()
    {
        $authUser = Auth::user();
        $clinicId = $authUser->parent_id ?: $authUser->id;
        $reservationIds = Reservations::where('clinic_id', $clinicId)->pluck('id');

        $messages = ReservationChat::with(['reservation.user'])
            ->whereIn('id', function ($query) use ($reservationIds) {
                $query->selectRaw('MAX(id)')
                    ->from('reservation_chats')
                    ->whereIn('reservation_id', $reservationIds)
                    ->groupBy('reservation_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $chatList = [];

        foreach ($messages as $lastMessage) {
            $otherUser = optional($lastMessage->reservation)->user;

            if (!$otherUser) {
                continue;
            }

            $unreadCount = ReservationChat::where('sender_id', $otherUser->id)
                ->where('receiver_id', $authUser->id)
                ->where('reservation_id', $lastMessage->reservation_id)
                ->where('is_read', 0)
                ->count();

            $chatList[] = [
                'user' => $otherUser,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
                'reservation_id' => $lastMessage->reservation_id,
            ];
        }

        $chatList = collect($chatList)->sortByDesc(function ($chat) {
            return $chat['last_message']->created_at;
        })->values();

        return view('reception.chat-list', compact('chatList'));
    }
}
