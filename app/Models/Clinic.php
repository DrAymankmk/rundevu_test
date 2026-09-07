<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasSeo;

class Clinic extends Authenticatable
{
    use HasFactory, HasRoles, HasSeo;
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'image', 'qr_code', 'status', 'app_type', 'parent_id', 'city_id', 'lat', 'lng', 'address',
        'gender', 'date_created','package_end_date', 'communication_officer','communication_officer_phone', 'specialization', 'firebase_token', 'platform', 'device_token', 'jwt_token', 'info', 'info_ar', 'consultation_price', 'degree_id', 'ID_Number',
        'facebook_url', 'instagram_url', 'tiktok_url', 'snapchat_url', 'youtube_url'
        ,'is_manager','nursing_point_id','notes','role_id','points_enabled','points_category','enabled_modules',
        'license_number','medical_commercial_license','alternative_phone'
    ];

    protected $casts = [
        'enabled_modules' => 'array',
        'points_enabled' => 'boolean',
        'consultation_price' => 'decimal:2',
    ];

    public function contractOwner()
    {
        if ((int) $this->app_type === 3 && $this->parent_id) {
            return self::find($this->parent_id);
        }

        return $this;
    }

    public function contract()
    {
        return $this->hasOne(ClinicContract::class, 'clinic_id');
    }

    public function isVerifiedForInstantBooking(): bool
    {
        $clinic = $this->contractOwner();
        $contract = $clinic ? $clinic->contract : null;

        if (!$clinic || !$contract) {
            return false;
        }

        return (bool) $contract->rendezvous_badge_enabled
            && (
//                (string) $contract->contract_model !== ClinicContract::ANNUAL_SUBSCRIPTION
//                ||
                empty($contract->annual_subscription_ends_at)
                || $contract->annual_subscription_ends_at->gte(now()->startOfDay())
            );
    }

    public function bookingPolicy(): array
    {
        $clinic = $this->contractOwner();
        $contract = $clinic && $clinic->contract
            ? $clinic->contract
            : new ClinicContract(ClinicContract::defaultAttributes());
        $contractModel = $contract->contract_model;
        $isVerified = $this->isVerifiedForInstantBooking();
        $bookingFlow = $isVerified ? 'instant' : 'request';
        $bookingMessage = $isVerified
            ? ''
            : 'تم استلام طلبك، وسوف يتم تأكيد الموعد النهائي معك من قبل العيادة.';

        return [
            'contract_model' => $contractModel,
            'commission_rate' => (float) ($contract->commission_rate ?? 0),
            'is_verified' => $isVerified,
            'badge_enabled' => $isVerified,
            'badge_name' => 'Rendezvous Badge',
            'badge_message' => $isVerified
                ? 'هذه العيادة تلتزم بالموعد المحجوز على شكل حجز مؤكد بمجرد اتمام الحجز.'
                : '',
            'booking_flow' => $bookingFlow,
            'booking_message' => $bookingMessage,
            'allows_instant_booking' => $isVerified,
            'allows_booking_request' => !$isVerified,
            'requires_doctor_selection' => true,
            'requires_appointment_selection' => $isVerified,
            'booking_action' => $isVerified ? 'choose_doctor_and_appointment' : 'send_booking_request',
            'booking_action_label_ar' => $isVerified ? 'احجز موعد' : 'إرسال طلب حجز',
            'booking_action_label_en' => $isVerified ? 'Book appointment' : 'Send booking request',
            'booking_instructions_ar' => $isVerified
                ? 'اختر الطبيب والموعد المناسب لتأكيد الحجز مباشرة.'
                : 'اختر الطبيب وأرسل طلب الحجز، وسوف تتواصل العيادة معك لتأكيد الموعد.',
            'booking_instructions_en' => $isVerified
                ? 'Choose the doctor and appointment time to confirm the booking immediately.'
                : 'Choose the doctor and send a booking request. The clinic will contact you to confirm the appointment.',
            'payment_method' => $contract->payment_method ?? ClinicContract::PAYMENT_CASH,
        ];
    }


    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('media/clinics/' . $value);
        } else {
            return asset('media/logo/logo.png');
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


    public function getQrCodeAttribute($value)
    {
        if ($value) {
            return asset('media/clinics/qr_code/' . $value);
        } else {
            return asset('media/clinics/user.png');
        }
    }

    public function setQrCodeAttribute($value)
    {
        if ($value) {
            $img_name = time() . rand(1111, 9999) . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('media/clinics/qr_code/'), $img_name);
            $this->attributes['qr_code'] = $img_name;
        }
    }


    public static function filterByLatLng($myLat, $myLng, $radius)
    {

        return Clinic::select(DB::raw('*, ( 6367 * acos( cos( radians(' . $myLat . ') ) * cos( radians( lat ) ) * cos(
   radians( lng ) - radians(' . $myLng . ') ) + sin( radians(' . $myLat . ') ) * sin( radians( lat ) ) ) ) AS
   distance'))
            ->where('clinics.app_type', 1)
            ->where('clinics.status', 1)
            ->having('distance', '<', $radius)
            ->orderBy('distance');

//       return $haversine = "(6371 * acos(cos(radians($myLat))
//                           * cos(radians(clinics.lat))
//                           * cos(radians(clinics.lng)
//                           - radians($myLng))
//                           + sin(radians($myLat))
//                           * sin(radians(clinics.lat))))";
//        return Clinic::select('*')
//            ->selectRaw("{$haversine} AS distance")
//            ->whereRaw("{$haversine} < ?", [$radius])
//            ->where('clinics.app_type', 1)
//            ->where('clinics.status',1)
//            ->select('id', 'info', 'name', 'image', 'lat', 'lng', 'address')->orderBy('id', 'asc');
    }


    function clinic_doctor()
    {
        return $this->belongsTo(Clinic::class, 'parent_id')->select('id', 'name', 'image', DB::raw('DATE(created_at) as created_date'));
    }

    function owner () {
        return $this->belongsTo(Clinic::class,'parent_id');
    }

    function posts () {
        return $this->hasMany(Posts::class,'clinic_id');
    }

    function notifications()
    {
        return $this->hasMany(Notifications::class, 'clinic_id')->count();
    }

    public function my_points()
    {
        return $this->hasMany(ClinicPoint::class, 'clinic_id')->sum('point');
    }

    public function clinic_points()
    {
        return $this->hasMany(ClinicPoint::class, 'clinic_id');
    }

    function city () {
        return $this->belongsTo(City::class,'city_id');
    }

    function role () {
        return $this->belongsTo(Role::class,'role_id');
    }



    function specialties()
    {
        return $this->hasMany(ClinicSpecialist::class, 'clinic_id')->where('type', 1)->select('id', 'specialty_id','clinic_id');
    }

      function specialty()
    {
        return $this->hasMany(ClinicSpecialist::class, 'clinic_id')->where('type', 1)->select('id', 'specialty_id','clinic_id');
    }

    function sub_specialties()
    {
        return $this->hasMany(ClinicSpecialist::class, 'clinic_id')->where('type', 2)->select('id', 'specialty_id','clinic_id');
    }

    function working_days()
    {
        return $this->hasMany(WorkingDays::class, 'pharmacist_id')->select('id', 'day_id', 'check_in_date', 'check_out_date');

    }

    function reception_staff()
    {
        return $this->hasMany(Clinic::class, 'parent_id')->where('app_type', 2)->select('id', 'name', 'phone', 'image');
    }

    function medical_staff()
    {
        return $this->hasMany(Clinic::class, 'parent_id')
            ->where('app_type', 3)
            ->select('id', 'parent_id', 'app_type', 'name', 'phone', 'image', 'info', 'info_ar', 'consultation_price');
    }

    function branches()
    {
        return $this->hasMany(Clinic::class, 'parent_id')
            ->where('app_type', 7)
            ->where('status', 1)
            ->select('id', 'parent_id', 'app_type', 'name', 'email', 'phone', 'image', 'lat', 'lng', 'address', 'status');
    }



//    function visitor_rating () {
//        return $this->hasMany(ClinicRating::class,'clinic_id')->select('id','name','phone','image','rate','comment');
//    }

    static function visitor_rating($clinic_id)
    {
        $rating_list = [];
        $visitor_rating = ClinicRating::with('users')->where('clinic_id', $clinic_id)->where('comment', '!=', null)->orderBy('id', 'desc')->get();
        foreach ($visitor_rating as $rating) {
            $rating_item['id'] = $rating->id;
            $rating_item['name'] = $rating->users->name;
            $rating_item['image'] = $rating->users->image;
            $rating_item['comment'] = $rating->comment;
            $rating_item['rate'] = round($rating->avg('rate_value'), 2);
            $rating_list [] = $rating_item;
        }
        return $rating_list;


    }

    // app type
    static function app_type_account($type)
    {
        $app_type =  AppType::where('id',$type)->first();
        if (app()->getLocale() == 'en') {
            return $app_type->name_en;
        } else {
            return $app_type->name_ar;
        }

    }

    // title job
    static function title_job($department)
    {
        if ($department == 2) {
            return trans('admin.receptionist');
        } else if ($department == 3) {
            return trans('admin.medical_staff');
        } else if ($department == 4) {
            return trans('admin.lab_staff');
        } else if ($department == 5) {
            return trans('admin.pharmacy_staff');
        } else {
            return trans('admin.employee');
        }

    }

    function employee_shifts()
    {
        return $this->hasMany(ShiftEmployee::class, 'employee_id')->OrderBy('id','desc');
    }

    function supervisor_permission()
    {
        return $this->hasMany(ClinicsPermissions::class, 'admin_id');
    }

    function clinicPointNursing()
    {
        return $this->belongsTo(ClinicPointNursing::class, 'nursing_point_id');
    }
    public function patientService () {

        return $this->hasMany(PatientService::class,'nurse_id');
    }
    public function emergencies()
    {
        return $this->belongsToMany(Emergency::class, 'doctor_emergencies', 'doctor_id', 'emergency_id');
    }
    public function emergenciesNurse()
    {
        return $this->belongsToMany(Emergency::class, 'doctor_emergencies', 'nurse_id', 'emergency_id');
    }

    function doctors()
    {
        return $this->hasMany(Clinic::class, 'parent_id')->where('app_type',3);
    }

    function labs_rays()
    {
        return $this->hasMany(Clinic::class, 'parent_id')->whereIn('app_type',[25,26]);
    }

    function reservations()
    {
        return $this->hasMany(Reservations::class, 'doctor_id');
    }

    function reservations_clinics()
    {
        return $this->hasMany(Reservations::class, 'clinic_id')->latest();
    }

    function reservations_reception()
    {
        return $this->hasMany(Reservations::class, 'reception_id');
    }

    function degree()
    {
        return $this->belongsTo(DoctorDegree::class, 'degree_id');
    }

    function complaints()
    {
        return $this->hasMany(ComplaintBox::class, 'clinic_id');
    }

    function reservations_done()
    {
        return $this->hasMany(Reservations::class, 'doctor_id')->where('status_id',6);
    }

    function reservations_cancel()
    {
        return $this->hasMany(Reservations::class, 'doctor_id')->where('status_id',[3,4,5]);
    }

    function condition()
    {
        return $this->hasOne(DoctorCondition::class, 'doctor_id', 'id');
    }

    function receptions()
    {
        return $this->hasMany(Clinic::class, 'parent_id')->where('app_type',2);
    }

    public function subscriptions()
    {
        return $this->hasMany(SubscriptionsPackageClinic::class);
    }

    public function currentPackage()
    {
        return $this->hasOneThrough(
            Package::class,
            SubscriptionsPackageClinic::class,
            'clinic_id',    // FK في subscriptions_package_clinics
            'id',           // PK في packages
            'id',           // PK في clinics
            'package_id'    // FK في subscriptions_package_clinics
        )
            ->where('subscriptions_package_clinics.status', 1)
            ->whereDate('subscriptions_package_clinics.end_date', '>=', now());
    }

    public function shifts()
    {
        return $this->hasMany(ShiftEmployee::class,'employee_id');
    }

    public function loyalty_coupons()
    {
        return $this->hasMany(LoyaltyRewardCoupon::class, 'clinic_id');
    }

    public function loyalty_redemptions()
    {
        return $this->hasMany(LoyaltyCouponRedemption::class, 'clinic_id');
    }

    public function appType()
    {
        return $this->belongsTo(AppType::class, 'app_type');
    }

    public static function resolveOrganizationId(?int $clinicId): ?int
    {
        if (!$clinicId) {
            return null;
        }

        $clinic = self::find($clinicId);
        if (!$clinic) {
            return null;
        }

        if (in_array((int) $clinic->app_type, [1, 4, 5, 7], true)) {
            return (int) $clinic->id;
        }

        return $clinic->parent_id ? (int) $clinic->parent_id : (int) $clinic->id;
    }

    public static function isLoyaltyEnabledForClinic(?int $clinicId): bool
    {
        $organizationId = self::resolveOrganizationId($clinicId);

        if (!$organizationId) {
            return false;
        }

        return (bool) self::where('id', $organizationId)->value('points_enabled');
    }

    public function organizationClinicId(): int
    {
        if ((int) $this->app_type === 7) {
            return (int) $this->id;
        }

        return (int) ($this->parent_id ?: $this->id);
    }

    public function hasLoyaltyEnabled(): bool
    {
        $organizationId = $this->organizationClinicId();

        return (bool) self::where('id', $organizationId)->value('points_enabled');
    }

    public function isPointsOnlyAccess(): bool
    {
        if (! is_array($this->enabled_modules)) {
            return false;
        }

        $enabled = array_values(array_unique(array_map('strval', $this->enabled_modules)));
        sort($enabled);

        $pointsOnly = array_values(config('clinic_modules.points_only_modules', ['points']));
        sort($pointsOnly);

        return $enabled === $pointsOnly;
    }

    /**
     * @return array<int, string>
     */
    public function selectedModuleKeys(): array
    {
        if ($this->enabled_modules === null) {
            return [];
        }

        return array_values(array_unique(array_map('strval', (array) $this->enabled_modules)));
    }

    /**
     * @return array<int, string>
     */
    public function displayModuleKeys(): array
    {
        if ($this->enabled_modules === null) {
            return array_keys(config('clinic_modules.modules', []));
        }

        return $this->selectedModuleKeys();
    }

}
