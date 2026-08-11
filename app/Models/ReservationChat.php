<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationChat extends Model
{
    use HasFactory;

    public $fillable = ['user_id', 'sender_id','receiver_id', 'reservation_id', 'message', 'file', 'record', 'media_flag', 'sender_type','is_read'];

    function messages () {
        return $this->belongsTo(ReservationChat::class,'created_at');
    }

    public function getFileAttribute($value)
    {
        if ($value) {
            return asset('media/reservations/chat/' . $value);
        }
    }

    public function setFileAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $img_name = uniqid() . '.' . $ext;
            $value->move(public_path('media/reservations/chat/'), $img_name);
            $this->attributes['file'] = $img_name;
        }
    }


    public function getRecordAttribute($value)
    {
        if ($value) {
            return asset('media/reservations/chat/' . $value);
        }
    }

    public function setRecordAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $img_name = uniqid() . '.' . $ext;
            $value->move(public_path('media/reservations/chat/'), $img_name);
            $this->attributes['record'] = $img_name;
        }
    }

    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    function reception()
    {
        return $this->belongsTo(Clinic::class, 'reception_id');
    }

    public function sender()
    {
        if ($this->sender_type == 2) {
            return $this->belongsTo(Clinic::class, 'sender_id');
        }
        return $this->belongsTo(User::class, 'sender_id');
    }

    // علاقة ديناميكية للمستقبل
    public function receiver()
    {
        if ($this->sender_type == 2) {
            return $this->belongsTo(Clinic::class, 'receiver_id');
        }
        return $this->belongsTo(User::class, 'receiver_id');
    }

    function reservation()
    {
        return $this->belongsTo(Reservations::class, 'reservation_id');
    }



    public static function get_messages_chat($input, $date)
    {
        $query = ReservationChat::where('reservation_id',$input->reservation_id)->whereDate('created_at', $date)->get();
        $chat_list = [];
        foreach ($query as $chat) {
            $chat_item['id'] = $chat->id;
            $chat_item['message'] = !empty($chat->message) ? $chat->message : "";
            $chat_item['file'] = !empty($chat->file) ? $chat->file : "";
            $chat_item['record'] = !empty($chat->record) ? $chat->record : "";
            $chat_item['sender_type'] = $chat->sender_type;
            $chat_item['media_flag'] = $chat->media_flag;
            $chat_item['time'] = date('g:i a',strtotime($chat->created_at));
            $chat_list[] = $chat_item;
        }
        return $chat_list;
    }
    


}
