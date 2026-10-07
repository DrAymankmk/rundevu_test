<?php

return [

    'enabled' => env('WEBSITE_VISIT_LOGS_ENABLED', true),

    'exclude_paths' => [
        'sitemap.xml',
        'robots.txt',
        'up',
        'clear',
        'pusher',
        'test',
    ],

    'exclude_path_prefixes' => [
        'admin',
        'api',
        'build',
        'frontend/assets',
        'storage',
        'vendor',
    ],

    'exclude_extensions' => [
        'css', 'js', 'map', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico',
        'woff', 'woff2', 'ttf', 'eot', 'pdf', 'xml', 'txt', 'json',
    ],

    'exclude_ip_prefixes' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('WEBSITE_VISIT_LOGS_EXCLUDE_IPS', ''))
    ))),

    'heartbeat_seconds' => (int) env('WEBSITE_VISIT_LOGS_HEARTBEAT', 15),

    'max_events_per_visit' => 50,

    'retention_days' => (int) env('WEBSITE_VISIT_LOGS_RETENTION_DAYS', 180),

    'cookie_name' => 'website_visitor_token',

    'cookie_minutes' => 60 * 24 * 365,

    'bot_ua_patterns' => [
        'Googlebot' => 'Googlebot',
        'bingbot' => 'Bingbot',
        'Slurp' => 'Yahoo',
        'DuckDuckBot' => 'DuckDuckBot',
        'Baiduspider' => 'Baiduspider',
        'YandexBot' => 'YandexBot',
        'facebookexternalhit' => 'Facebook',
        'Facebot' => 'Facebook',
        'Twitterbot' => 'Twitterbot',
        'LinkedInBot' => 'LinkedInBot',
        'WhatsApp' => 'WhatsApp',
        'TelegramBot' => 'TelegramBot',
        'Applebot' => 'Applebot',
        'SemrushBot' => 'SemrushBot',
        'AhrefsBot' => 'AhrefsBot',
        'DotBot' => 'DotBot',
        'PetalBot' => 'PetalBot',
        'bytespider' => 'Bytespider',
        'GPTBot' => 'GPTBot',
        'ClaudeBot' => 'ClaudeBot',
        'ChatGPT-User' => 'ChatGPT',
        'CCBot' => 'CCBot',
        'MJ12bot' => 'MJ12bot',
        'Sogou' => 'Sogou',
        'Exabot' => 'Exabot',
        'ia_archiver' => 'Alexa',
        'HeadlessChrome' => 'HeadlessChrome',
        'PhantomJS' => 'PhantomJS',
        'curl' => 'curl',
        'wget' => 'wget',
        'python-requests' => 'python-requests',
        'Python-urllib' => 'python-urllib',
        'Go-http-client' => 'Go-http-client',
        'Java/' => 'Java',
        'libwww-perl' => 'libwww-perl',
        'Scrapy' => 'Scrapy',
        'httpclient' => 'HTTPClient',
        'bot' => 'Bot',
        'spider' => 'Spider',
        'crawler' => 'Crawler',
    ],

];
