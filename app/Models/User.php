<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'image','active', 'status', 'city_id', 'region_id', 'lat', 'lng', 'address',
        'gender', 'dob', 'ID_Number','referral_code', 'firebase_token', 'platform', 'device_token', 'jwt_token','parent_id',
        'nationality_id', 'country_id', 'address_1','address_2', 'postal_code', 'mobile_number', 'file_number', 'bill_number','insurance_card_number',
        'card_expiry_date', 'company_id', 'class_id','reception_id','national_id','expired_date','package_id'
    ];


    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/users/' . $value);
        } else {
            return asset('media/user.png');
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
        QrCode::format('png')->size(500)->errorCorrection('H')->generate("$ID_Number", public_path('media/users/qr_code/' . $ID_Number . '.png'));
    }

    public function parent () {
        return $this->hasMany(User::class,'parent_id');
    }

    public function company () {
        return $this->belongsTo(InsuranceCompanies::class,'company_id');
    }

    function city()
    {
        return $this->belongsTo(City::class,'city_id');

    }


    function reservation_completed()
    {
        return $this->hasMany(Reservations::class,'user_id')->where('status_id',6);

    }

    function reservation_not_completed()
    {
        return $this->hasMany(Reservations::class,'user_id')->where('status_id','!=',6);
    }



    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function checkJwtAuth($input)
    {
        $token = request()->bearerToken();
        return User::where('jwt_token', $token)->first();
    }


    function patient_services () {
        return $this->hasMany(PatientService::class,'user_id');
    }

    function nationality () {
        return $this->belongsTo(Country::class,'nationality_id');
    }

    function region () {
        return $this->belongsTo(Regions::class,'region_id');
    }
    function class () {
        return $this->belongsTo(InsuranceClasses::class,'class_id');
    }

    function package () {
        return $this->belongsTo(Package::class,'package_id');
    }

    function loyalty_points()
    {
        return $this->hasMany(LoyaltyPointTransaction::class, 'user_id');
    }

    function loyalty_redemptions()
    {
        return $this->hasMany(LoyaltyCouponRedemption::class, 'user_id');
    }

}
