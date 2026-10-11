<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebsiteLink extends Model
{
    public const TYPE_SOCIAL = 'social';
    public const TYPE_STORE = 'store';

    public const KEY_APPLE = 'apple';
    public const KEY_GOOGLE_PLAY = 'google_play';

    protected $table = 'website_links';

    protected $fillable = [
        'key',
        'type',
        'url',
        'icon',
        'brand_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function presets(): array
    {
        return [
            'facebook' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-facebook-f',
                'brand_color' => '#1877F2',
            ],
            'instagram' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-instagram',
                'brand_color' => '#E4405F',
            ],
            'snapchat' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-snapchat',
                'brand_color' => '#FFFC00',
            ],
            'twitter' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-x-twitter',
                'brand_color' => '#000000',
            ],
            'tiktok' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-tiktok',
                'brand_color' => '#010101',
            ],
            'youtube' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-youtube',
                'brand_color' => '#FF0000',
            ],
            'linkedin' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-linkedin-in',
                'brand_color' => '#0A66C2',
            ],
            'whatsapp' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-whatsapp',
                'brand_color' => '#25D366',
            ],
            'telegram' => [
                'type' => self::TYPE_SOCIAL,
                'icon' => 'fab fa-telegram-plane',
                'brand_color' => '#229ED9',
            ],
            'apple' => [
                'type' => self::TYPE_STORE,
                'icon' => 'fab fa-apple',
                'brand_color' => '#000000',
            ],
            'google_play' => [
                'type' => self::TYPE_STORE,
                'icon' => 'fab fa-google-play',
                'brand_color' => '#34A853',
            ],
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(WebsiteLinkTranslation::class, 'website_link_id');
    }

    public function translation(?string $locale = null): ?WebsiteLinkTranslation
    {
        $locale = $locale ?? app()->getLocale();

        if ($this->relationLoaded('translations')) {
            return $this->translations->firstWhere('locale', $locale);
        }

        return $this->translations()->where('locale', $locale)->first();
    }

    public function displayTitle(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');

        $title = $this->translation($locale)?->title;
        if (filled($title)) {
            return $title;
        }

        if ($locale !== $fallback) {
            $fallbackTitle = $this->translation($fallback)?->title;
            if (filled($fallbackTitle)) {
                return $fallbackTitle;
            }
        }

        $langKey = 'main.social_platform_' . $this->key;
        if (trans()->has($langKey)) {
            return __($langKey);
        }

        return ucwords(str_replace('_', ' ', $this->key));
    }

    public function displayDescription(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');

        $description = $this->translation($locale)?->description;
        if (filled($description)) {
            return $description;
        }

        if ($locale !== $fallback) {
            $fallbackDescription = $this->translation($fallback)?->description;
            if (filled($fallbackDescription)) {
                return $fallbackDescription;
            }
        }

        $langKey = 'main.social_platform_' . $this->key . '_desc';
        if (trans()->has($langKey)) {
            return __($langKey);
        }

        return '';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSocial($query)
    {
        return $query->where('type', self::TYPE_SOCIAL);
    }

    public function scopeStore($query)
    {
        return $query->where('type', self::TYPE_STORE);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
