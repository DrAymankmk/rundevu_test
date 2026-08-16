<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SeoMeta extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'seo_meta';

    protected $fillable = [
        'canonical_url',
        'robots',
        'og_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SeoMetaTranslation::class);
    }

    public function translation(?string $locale = null): ?SeoMetaTranslation
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations()->where('locale', $locale)->first();
    }

    public function getTranslatedAttribute(string $attribute, ?string $locale = null, ?string $fallbackLocale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $fallbackLocale = $fallbackLocale ?? config('app.fallback_locale', 'en');

        $translation = $this->translation($locale);
        if ($translation && filled($translation->{$attribute})) {
            return $translation->{$attribute};
        }

        if ($locale !== $fallbackLocale) {
            $fallback = $this->translation($fallbackLocale);
            if ($fallback && filled($fallback->{$attribute})) {
                return $fallback->{$attribute};
            }
        }

        return null;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('og_image')->singleFile();
    }
}
