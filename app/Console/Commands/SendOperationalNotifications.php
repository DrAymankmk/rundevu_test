<?php

namespace App\Console\Commands;

use App\Models\Clinic;
use App\Models\ClinicOffer;
use App\Models\Notifications;
use App\Models\Reservations;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOperationalNotifications extends Command
{
    protected $signature = 'takafol:send-operational-notifications';

    protected $description = 'Create appointment and offer reminder notifications.';

    public function handle()
    {
        $this->sendAppointmentReminders();
        $this->sendPendingReservationAlerts();
        $this->sendOfferAlerts();

        return Command::SUCCESS;
    }

    private function sendAppointmentReminders()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        Reservations::with(['user', 'clinic'])
            ->whereDate('date', $tomorrow)
            ->where('status_id', 1)
            ->chunkById(100, function ($reservations) {
                foreach ($reservations as $reservation) {
                    $messageAr = 'تذكير بموعد الحجز رقم ' . $reservation->booking_number . ' بتاريخ ' . $reservation->date . ' الساعة ' . $reservation->appointment;
                    $messageEn = 'Reminder for booking #' . $reservation->booking_number . ' on ' . $reservation->date . ' at ' . $reservation->appointment;

                    $this->createNotificationOnce([
                        'clinic_id' => $reservation->clinic_id,
                        'user_id' => $reservation->user_id,
                        'receiver_id' => $reservation->user_id,
                        'type' => 1,
                        'app_type' => 1,
                        'title_ar' => 'تذكير بموعد',
                        'title_en' => 'Appointment reminder',
                        'message_ar' => $messageAr,
                        'message_en' => $messageEn,
                    ]);

                    $this->sendEmail($reservation->user->email ?? null, 'Appointment reminder', $messageEn);
                }
            });
    }

    private function sendPendingReservationAlerts()
    {
        Reservations::where('status_id', 2)
            ->whereDate('date', '>=', now()->toDateString())
            ->chunkById(100, function ($reservations) {
                foreach ($reservations as $reservation) {
                    $messageAr = 'يوجد حجز يحتاج تأكيد رقم ' . $reservation->booking_number;
                    $messageEn = 'Booking #' . $reservation->booking_number . ' needs confirmation.';

                    $this->createNotificationOnce([
                        'clinic_id' => $reservation->clinic_id,
                        'receiver_id' => $reservation->reception_id,
                        'type' => 2,
                        'app_type' => 2,
                        'title_ar' => 'حجز يحتاج تأكيد',
                        'title_en' => 'Booking needs confirmation',
                        'message_ar' => $messageAr,
                        'message_en' => $messageEn,
                    ]);
                }
            });
    }

    private function sendOfferAlerts()
    {
        $today = Carbon::today();
        $after48Hours = Carbon::now()->addHours(48)->toDateString();

        ClinicOffer::where('status', 1)
            ->whereNotNull('start_date')
            ->whereDate('start_date', $today->toDateString())
            ->chunkById(100, function ($offers) {
                foreach ($offers as $offer) {
                    $messageAr = 'Offer "' . $offer->title_ar . '" starts today.';
                    $messageEn = 'Offer "' . $offer->title_en . '" starts today.';

                    $this->createNotificationOnce([
                        'clinic_id' => $offer->clinic_id,
                        'type' => 0,
                        'app_type' => 1,
                        'title_ar' => 'Offer started',
                        'title_en' => 'Offer started',
                        'message_ar' => $messageAr,
                        'message_en' => $messageEn,
                    ]);
                }
            });

        ClinicOffer::with('specialty')
            ->whereNotNull('end_date')
            ->whereDate('end_date', $after48Hours)
            ->chunkById(100, function ($offers) {
                foreach ($offers as $offer) {
                    $messageAr = 'العرض "' . $offer->title_ar . '" سينتهي خلال 48 ساعة.';
                    $messageEn = 'Offer "' . $offer->title_en . '" will expire within 48 hours.';

                    $this->createNotificationOnce([
                        'clinic_id' => $offer->clinic_id,
                        'type' => 0,
                        'app_type' => 1,
                        'title_ar' => 'تنبيه انتهاء عرض',
                        'title_en' => 'Offer expiry alert',
                        'message_ar' => $messageAr,
                        'message_en' => $messageEn,
                    ]);

                    $clinic = Clinic::find($offer->clinic_id);
                    $this->sendEmail($clinic->email ?? null, 'Offer expiry alert', $messageEn);
                }
            });

        ClinicOffer::whereNotNull('end_date')
            ->whereDate('end_date', '<', $today->toDateString())
            ->where('status', 1)
            ->update(['status' => 0]);

        Clinic::whereIn('app_type', [1, 7])
            ->chunkById(100, function ($clinics) use ($today) {
                foreach ($clinics as $clinic) {
                    $hasActiveOffers = ClinicOffer::where('clinic_id', $clinic->id)
                        ->where('status', 1)
                        ->where(function ($query) use ($today) {
                            $query->whereNull('end_date')
                                ->orWhereDate('end_date', '>=', $today->toDateString());
                        })
                        ->exists();

                    if ($hasActiveOffers) {
                        continue;
                    }

                    $this->createNotificationOnce([
                        'clinic_id' => $clinic->id,
                        'receiver_id' => $clinic->id,
                        'type' => 2,
                        'app_type' => $clinic->app_type,
                        'title_ar' => 'لا توجد عروض نشطة',
                        'title_en' => 'No active offers',
                        'message_ar' => 'لا توجد عروض نشطة حاليا، برجاء إضافة أو تجديد عرض.',
                        'message_en' => 'There are no active offers. Please add or renew an offer.',
                    ]);
                }
            });
    }

    private function createNotificationOnce(array $data)
    {
        $exists = Notifications::whereDate('created_at', now()->toDateString())
            ->where('title_en', $data['title_en'])
            ->where('message_en', $data['message_en'])
            ->exists();

        if (!$exists) {
            Notifications::create($data);
        }
    }

    private function sendEmail($email, $subject, $message)
    {
        if (!$email) {
            return;
        }

        try {
            Mail::raw($message, function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
