<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class invoices extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number','payment_number', 'user_id', 'reception_id', 'doctor_id', 'reservation_id', 'payment_method', 'company_id', 'payment_status', 'total_price', 'discount', 'patient_tax',
        'company_tax', 'company_total_deductible', 'total_amount_paid','other_info', 'status',
    ];
    public static function generate_qrCode($invoice_number)
    {
        QrCode::format('png')->size(500)->errorCorrection('H')->generate("$invoice_number", public_path('media/invoices/' . $invoice_number . '.png'));
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

    function services () {
        return $this->hasMany(PatientService::class,'invoice_id');
    }

}
