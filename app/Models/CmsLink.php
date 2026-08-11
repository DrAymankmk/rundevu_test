<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CmsLink extends Model
{
    use HasFactory;

    protected $table = 'cms_links';

    protected $fillable = [
        'linkable_id',
        'linkable_type',
        'name',
        'link',
        'icon',
        'target',
        'type',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent linkable model (CmsPage, CmsSection, or CmsItem)
     */
    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all translations for the link
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CmsLinkTranslation::class, 'cms_link_id');
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

        // Fallback to main model attribute
        return $this->$attribute ?? null;
    }

    /**
     * Scope to get only active links
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
     * Scope to filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
