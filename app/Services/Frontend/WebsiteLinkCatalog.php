<?php

namespace App\Services\Frontend;

use App\Models\WebsiteLink;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class WebsiteLinkCatalog
{
    private static ?bool $tableExists = null;

    private ?Collection $records = null;

    public function socialPlatforms(): Collection
    {
        $fromDb = $this->records()
            ->where('type', WebsiteLink::TYPE_SOCIAL)
            ->where('is_active', true)
            ->filter(static fn (WebsiteLink $link) => filled($link->url))
            ->values();

        if ($fromDb->isNotEmpty()) {
            return $fromDb->map(fn (WebsiteLink $link) => $this->toPlatformArray($link))->values();
        }

        return $this->configSocialPlatforms();
    }

    public function storeUrl(string $key, ?string $fallback = null): string
    {
        $key = $this->normalizeStoreKey($key);

        $link = $this->records()
            ->where('type', WebsiteLink::TYPE_STORE)
            ->firstWhere('key', $key);

        if ($link) {
            if (! $link->is_active || ! filled($link->url)) {
                return '';
            }

            return (string) $link->url;
        }

        $configUrl = (string) config('social.stores.' . $key . '.url', '');
        if (filled($configUrl)) {
            return $configUrl;
        }

        return (string) ($fallback ?? '');
    }

    public function appleUrl(?string $fallback = null): string
    {
        return $this->storeUrl(WebsiteLink::KEY_APPLE, $fallback);
    }

    public function googlePlayUrl(?string $fallback = null): string
    {
        return $this->storeUrl(WebsiteLink::KEY_GOOGLE_PLAY, $fallback);
    }

    private function records(): Collection
    {
        if ($this->records !== null) {
            return $this->records;
        }

        if (! $this->tableExists()) {
            return $this->records = collect();
        }

        return $this->records = WebsiteLink::query()
            ->with('translations')
            ->ordered()
            ->get();
    }

    private function tableExists(): bool
    {
        if (self::$tableExists === null) {
            self::$tableExists = Schema::hasTable('website_links');
        }

        return self::$tableExists;
    }

    private function toPlatformArray(WebsiteLink $link): array
    {
        $color = $link->brand_color ?: '#3E66F3';

        return [
            'id' => $link->id,
            'key' => $link->key,
            'url' => $link->url,
            'icon' => $link->icon ?: 'fas fa-share-alt',
            'brand_color' => $color,
            'title' => $link->displayTitle(),
            'description' => $link->displayDescription(),
            'is_light_icon' => $this->isLightColor($link->key, $color),
        ];
    }

    private function configSocialPlatforms(): Collection
    {
        return collect(config('social.platforms', []))
            ->filter(static fn ($platform) => filled($platform['url'] ?? null))
            ->map(function ($platform) {
                $key = $platform['key'] ?? 'social';
                $color = $platform['brand_color'] ?? '#3E66F3';
                $titleKey = 'main.social_platform_' . $key;
                $descKey = 'main.social_platform_' . $key . '_desc';

                return [
                    'id' => null,
                    'key' => $key,
                    'url' => $platform['url'],
                    'icon' => $platform['icon'] ?? 'fas fa-share-alt',
                    'brand_color' => $color,
                    'title' => trans()->has($titleKey) ? __($titleKey) : ucwords(str_replace('_', ' ', $key)),
                    'description' => trans()->has($descKey) ? __($descKey) : '',
                    'is_light_icon' => $this->isLightColor($key, $color),
                ];
            })
            ->values();
    }

    private function normalizeStoreKey(string $key): string
    {
        $key = strtolower(trim($key));

        if (in_array($key, ['google', 'play', 'googleplay', 'android'], true)) {
            return WebsiteLink::KEY_GOOGLE_PLAY;
        }

        if (in_array($key, ['ios', 'appstore', 'app_store'], true)) {
            return WebsiteLink::KEY_APPLE;
        }

        return $key;
    }

    private function isLightColor(string $key, string $color): bool
    {
        if (in_array($key, ['snapchat'], true)) {
            return true;
        }

        $hex = ltrim($color, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return false;
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        return $luminance > 0.72;
    }
}
