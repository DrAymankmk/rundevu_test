<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UsersMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'image', 'status',
        'gender', 'dob','user_id','ID_Number'
    ];


    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/users/' . $value);
        } else {
            return asset('media/users/user.png');
        }
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/users/'), $img_name);
            $this->attributes['image'] = $img_name;
        }
    }


    // generate qr code
    public static function generate_qrCode($ID_Number)
    {
        QrCode::format('png')->size(500)->errorCorrection('H')->generate("$ID_Number", public_path('media/users/members/' . $ID_Number . '.png'));
    }
}
