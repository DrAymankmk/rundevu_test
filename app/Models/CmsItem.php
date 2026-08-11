<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\HasMediaRetrieval;

class CmsItem extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasMediaRetrieval;

    protected $table = 'cms_items';

    protected $fillable = [
        'cms_section_id',
        'type',
        'slug',
        'settings',
        'order',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the section that owns the item
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(CmsSection::class, 'cms_section_id');
    }

    /**
     * Get all translations for the item
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CmsItemTranslation::class, 'cms_item_id');
    }

    /**
     * Get all links for the item (polymorphic)
     */
    public function links()
    {
        return $this->morphMany(CmsLink::class, 'linkable');
    }

    /**
     * Get translation for a specific locale
     */
    public function translation(string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get translated attribute with fallback
     */
    public function getTranslatedAttribute(string $attribute, string $locale = null, string $fallbackLocale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $fallbackLocale = $fallbackLocale ?? config('app.fallback_locale', 'en');

        $translation = $this->translation($locale);
        
        if ($translation && !empty($translation->$attribute)) {
            return $translation->$attribute;
        }

        if ($locale !== $fallbackLocale) {
            $fallbackTranslation = $this->translation($fallbackLocale);
            if ($fallbackTranslation && !empty($fallbackTranslation->$attribute)) {
                return $fallbackTranslation->$attribute;
            }
        }

        return null;
    }

    /**
     * Scope to get only active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by the order column
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Register media collections for items
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('icons')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'])
            ->singleFile();

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(\App\Support\Cms\CmsGalleryMedia::allMimeTypes());
    }

    /**
     * Register media conversions for items
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonOptimized()
            ->nonQueued()
            ->performOnCollections('images', 'gallery', 'icons');

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->nonOptimized()
            ->nonQueued()
            ->performOnCollections('images', 'gallery', 'icons');
    }
}
