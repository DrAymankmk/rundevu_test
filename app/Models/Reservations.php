<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Reservations extends Model
{
    use HasFactory;

    public const SLOT_BLOCKING_STATUS_IDS = [1, 2, 3, 6];

    public $fillable = ['user_id','parent_id', 'clinic_id','doctor_id', 'reception_id','sub_specialist_id', 'status_id', 'booking_number','date','appointment','price','diagnosis','symptoms','clinical_examination','recommendations','notes','schedule_consultation_date','schedule_consultation_time','type','follow_up','payment_status','waiting_list','booking_flow','payment_method','payment_reference','platform_commission_rate','platform_commission_amount','clinic_net_amount','settlement_direction','financial_cycle_date'];

    protected $casts = [
        'platform_commission_rate' => 'decimal:2',
        'platform_commission_amount' => 'decimal:2',
        'clinic_net_amount' => 'decimal:2',
        'financial_cycle_date' => 'date',
    ];

    function vital_signs () {
        return $this->hasOne(VitalSigns::class,'reservation_id');
    }

    // generate qr code
    public static function generate_qrCode($booking_number)
    {
        QrCode::format('png')->size(500)->errorCorrection('H')->generate("$booking_number", public_path('media/reservations/' . $booking_number . '.png'));
    }

    public function scopeBlocksAppointmentSlot($query, $doctorId, $date, $appointment, $clinicId = null)
    {
        return $query
            ->where('doctor_id', $doctorId)
            ->when($clinicId, function ($query) use ($clinicId) {
                $query->where('clinic_id', $clinicId);
            })
            ->where(function ($query) use ($date, $appointment) {
                $query->where(function ($sub) use ($date, $appointment) {
                    $sub->where('date', $date)
                        ->where('appointment', $appointment);
                })->orWhere(function ($sub) use ($date, $appointment) {
                    $sub->where('schedule_consultation_date', $date)
                        ->where('schedule_consultation_time', $appointment);
                });
            })
            ->where(function ($query) {
                $query->whereNull('status_id')
                    ->orWhereIn('status_id', self::SLOT_BLOCKING_STATUS_IDS);
            });
    }

    function clinic () {
        return $this->belongsTo(Clinic::class,'clinic_id');
    }

    function doctor () {
        return $this->belongsTo(Clinic::class,'doctor_id');
    }

    function reception () {
        return $this->belongsTo(Clinic::class,'reception_id');
    }

    function user () {
        return $this->belongsTo(User::class,'user_id');
    }

    function reservation_status () {
        return $this->belongsTo(Status::class,'status_id');
    }

    function reservation_drugs () {

        return $this->hasMany(ReservationDrug::class,'reservation_id');
    }

    function invoice () {
        return $this->hasOne(invoices::class,'reservation_id');
    }

    function specialty () {

        return $this->belongsTo(Specialty::class,'sub_specialist_id');
    }
}
