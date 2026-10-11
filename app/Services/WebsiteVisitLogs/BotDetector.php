<?php

namespace App\Services\WebsiteVisitLogs;

class BotDetector
{
    /**
     * @return array{is_bot: bool, bot_name: string|null}
     */
    public function detect(?string $userAgent): array
    {
        $ua = trim((string) $userAgent);

        if ($ua === '') {
            return [
                'is_bot' => true,
                'bot_name' => 'UnknownBot',
            ];
        }

        $patterns = config('website_visit_logs.bot_ua_patterns', []);

        foreach ($patterns as $needle => $label) {
            if ($needle === '' || $label === '') {
                continue;
            }
            if (stripos($ua, (string) $needle) !== false) {
                return [
                    'is_bot' => true,
                    'bot_name' => (string) $label,
                ];
            }
        }

        if (mb_strlen($ua) < 12) {
            return [
                'is_bot' => true,
                'bot_name' => 'UnknownBot',
            ];
        }

        return [
            'is_bot' => false,
            'bot_name' => null,
        ];
    }
}
