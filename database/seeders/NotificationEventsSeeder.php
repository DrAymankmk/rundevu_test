<?php

namespace Database\Seeders;

use App\Models\NotificationEvent;
use Illuminate\Database\Seeder;

class NotificationEventsSeeder extends Seeder
{
    public function run()
    {
        $events = [
            [
                'key' => 'clinic.registered',
                'name_en' => 'Clinic registration',
                'name_ar' => 'تسجيل عيادة جديدة',
                'description_en' => 'Sent when a clinic completes subscription registration.',
                'description_ar' => 'يُرسل عند إتمام تسجيل عيادة جديدة عبر صفحة الاشتراك.',
            ],
            [
                'key' => 'demo.requested',
                'name_en' => 'Demo request',
                'name_ar' => 'طلب حجز موعد',
                'description_en' => 'Sent when a visitor submits a book-demo form.',
                'description_ar' => 'يُرسل عند إرسال نموذج حجز موعد تجريبي.',
            ],
        ];

        foreach ($events as $event) {
            NotificationEvent::query()->updateOrCreate(
                ['key' => $event['key']],
                $event
            );
        }
    }
}
