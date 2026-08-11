<?php

namespace App\Models;

use App\Events\Notify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    public $fillable = ['admin_id','user_id','clinic_id','receiver_id','type','app_type','image','title_en', 'title_ar','message_en','message_ar','status'];

    protected static function booted()
    {
        static::created(function (Notifications $notification) {
            if (!$notification->shouldBroadcastToAdminPanel()) {
                return;
            }

            broadcast(new Notify([
                'id' => $notification->id,
                'group_key' => $notification->broadcastGroupKey(),
                'title_ar' => $notification->title_ar,
                'title_en' => $notification->title_en,
                'message_ar' => $notification->message_ar,
                'message_en' => $notification->message_en,
                'receiver_id' => $notification->receiver_id,
                'clinic_id' => $notification->clinic_id,
                'app_type' => $notification->app_type,
                'type' => $notification->type,
            ]));
        });
    }

    public function shouldBroadcastToAdminPanel()
    {
        $isUserNotification = (int) $this->app_type === 1
            && !empty($this->user_id)
            && (empty($this->receiver_id) || (int) $this->receiver_id === (int) $this->user_id);

        if ($isUserNotification) {
            return false;
        }

        $request = request();

        if ($request && !$request->is('api/*')) {
            return false;
        }

        return true;
    }

    public static function createForAdminPanel($clinicId, array $data, $receptionId = null)
    {
        if (!$clinicId) {
            return;
        }

        $clinic = Clinic::select('id', 'app_type', 'parent_id')->find($clinicId);
        $adminClinicId = $clinic && $clinic->parent_id ? $clinic->parent_id : $clinicId;

        static::create(array_merge($data, [
            'clinic_id' => $adminClinicId,
            'receiver_id' => $adminClinicId,
            'type' => 2,
            'app_type' => 7,
        ]));

        $receptionIds = $receptionId
            ? collect([$receptionId])
            : Clinic::where('parent_id', $adminClinicId)->where('app_type', 2)->pluck('id');

        foreach ($receptionIds as $id) {
            static::create(array_merge($data, [
                'clinic_id' => $adminClinicId,
                'receiver_id' => $id,
                'type' => 2,
                'app_type' => 2,
            ]));
        }
    }

    public function broadcastGroupKey()
    {
        return sha1(implode('|', [
            $this->clinic_id,
            $this->title_en,
            $this->title_ar,
            $this->message_en,
            $this->message_ar,
        ]));
    }


    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/clinics/' . $value);
        } else {
            return asset('media/clinics/user.png');
        }
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/clinics/'), $img_name);
            $this->attributes['image'] = $img_name;
        }
    }

    public function admin()
    {
        return $this->belongsTo(Clinic::class,'admin_id');
    }

    public function clinics()
    {
        return $this->hasMany(Clinic::class,'clinic_id');
    }


}
