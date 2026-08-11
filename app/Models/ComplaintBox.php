<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintBox extends Model
{
    use HasFactory;
    public $table = 'complaint_boxes';

    public $fillable = ['user_id', 'clinic_id', 'image', 'complain', 'reply','created_at'];

    protected static function booted()
    {
        static::created(function (ComplaintBox $complaint) {
            $request = request();

            if (!$request || !$request->is('api/*')) {
                return;
            }

            Notifications::createForAdminPanel($complaint->clinic_id, [
                'title_ar' => 'شكوى جديدة',
                'title_en' => 'New complaint',
                'message_ar' => 'لديك شكوى جديدة من التطبيق.',
                'message_en' => 'A new complaint has been sent from the mobile app.',
            ]);
        });
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id')->select('id','name','phone','email','ID_Number','image','dob','address','created_at');
    }

    public function clinics()
    {
        return $this->belongsTo(Clinic::class,'clinic_id')->select('id','name','phone','image','created_at');
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/clinics/complain/' . $value);
        } else {
            return asset('media/clinics/user.png');
        }
    }
    public function setImageAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/clinics/complain/'), $img_name);
            $this->attributes['image'] = $img_name;
        }
    }
}
