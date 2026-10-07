<?php

namespace App\Services\WebsiteVisitLogs;

class VisitUrlDisplay
{
    /**
     * Decode percent-encoded URLs/paths (e.g. Arabic slugs) for readable display/storage.
     */
    public static function decode(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (strpos($value, '%') === false) {
            return $value;
        }

        $decoded = $value;
        // Decode nested encoding once or twice max.
        for ($i = 0; $i < 2; $i++) {
            $next = rawurldecode($decoded);
            if ($next === $decoded) {
                break;
            }
            $decoded = $next;
        }

        if (! mb_check_encoding($decoded, 'UTF-8')) {
            return $value;
        }

        return $decoded;
    }
}
