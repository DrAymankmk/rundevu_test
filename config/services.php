<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
    ],

    'fourjawaly' => [
        'app_id' => env('FOURJAWALY_APP_ID'),
        'app_secret' => env('FOURJAWALY_APP_SECRET'),
        'sender' => env('FOURJAWALY_SENDER'),
        'url' => env('FOURJAWALY_URL', 'https://api-sms.4jawaly.com/api/v1/account/area/sms/send'),
        'timeout' => env('FOURJAWALY_TIMEOUT', 30),
    ],

    /*
    | Google Search Console, GA4 (gtag.js), and Tag Manager.
    | Prefer GTM alone if GA4 is already loaded inside a GTM container.
    */
    'google' => [
        'site_verification' => env('GOOGLE_SITE_VERIFICATION'),
        'analytics_id' => env('GOOGLE_ANALYTICS_ID'),
        'tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID'),
    ],

];
