<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Social media profiles
    |--------------------------------------------------------------------------
    |
    | Used on the frontend social page and footer. Leave url empty to hide a
    | platform. Override via .env (SOCIAL_*_URL).
    |
    */
    'platforms' => [
        [
            'key' => 'facebook',
            'url' => env('SOCIAL_FACEBOOK_URL', ''),
            'icon' => 'fab fa-facebook-f',
            'brand_color' => '#1877F2',
        ],
        [
            'key' => 'instagram',
            'url' => env('SOCIAL_INSTAGRAM_URL', 'https://www.instagram.com/runde_vo'),
            'icon' => 'fab fa-instagram',
            'brand_color' => '#E4405F',
        ],
        [
            'key' => 'snapchat',
            'url' => env('SOCIAL_SNAPCHAT_URL', 'https://www.snapchat.com/@rundev'),
            'icon' => 'fab fa-snapchat',
            'brand_color' => '#FFFC00',
        ],
        [
            'key' => 'twitter',
            'url' => env('SOCIAL_TWITTER_URL', ''),
            'icon' => 'fab fa-x-twitter',
            'brand_color' => '#000000',
        ],
        [
            'key' => 'tiktok',
            'url' => env('SOCIAL_TIKTOK_URL', 'https://www.tiktok.com/@rundevo.app'),
            'icon' => 'fab fa-tiktok',
            'brand_color' => '#010101',
        ],
        [
            'key' => 'youtube',
            'url' => env('SOCIAL_YOUTUBE_URL', ''),
            'icon' => 'fab fa-youtube',
            'brand_color' => '#FF0000',
        ],
        [
            'key' => 'linkedin',
            'url' => env('SOCIAL_LINKEDIN_URL', ''),
            'icon' => 'fab fa-linkedin-in',
            'brand_color' => '#0A66C2',
        ],
        [
            'key' => 'whatsapp',
            'url' => env('SOCIAL_WHATSAPP_URL', ''),
            'icon' => 'fab fa-whatsapp',
            'brand_color' => '#25D366',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | App store links
    |--------------------------------------------------------------------------
    |
    | Fallback URLs used before website_links rows exist. Dashboard values
    | take precedence once the Social & App Links module is migrated.
    |
    */
    'stores' => [
        'apple' => [
            'url' => env(
                'APP_STORE_APPLE_URL',
                'https://apps.apple.com/us/app/randevu-%D8%B1%D8%A7%D9%86%D8%AF%D9%8A%D9%81%D9%88/id6761128352'
            ),
        ],
        'google_play' => [
            'url' => env(
                'APP_STORE_GOOGLE_URL',
                'https://play.google.com/store/apps/details?id=com.takaful.rendezvous'
            ),
        ],
    ],
];
