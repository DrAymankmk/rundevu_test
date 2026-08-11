<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fallback admin email
    |--------------------------------------------------------------------------
    |
    | Used when an event has no active recipients configured in the dashboard.
    |
    */
    'fallback_email' => env('NOTIFICATION_FALLBACK_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Event → Mailable mapping
    |--------------------------------------------------------------------------
    |
    | Keys must match notification_events.key in the database.
    | Add new events here when introducing new notifiable actions.
    |
    */
    'events' => [
        'clinic.registered' => [
            'mailable' => \App\Mail\Notifications\ClinicRegisteredMail::class,
        ],
        'demo.requested' => [
            'mailable' => \App\Mail\Notifications\DemoRequestedMail::class,
        ],
    ],
];
