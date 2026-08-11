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
use App\Support\Cms\CmsGalleryMedia;

class CmsSection extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasMediaRetrieval;

    protected $table = 'cms_sections';

    protected $fillable = [
        'cms_page_id',
        'name',
        'type',
        'template',
        'section_layout',
        'settings',
        'order',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the page that owns the section
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }

    /**
     * Get all translations for the section
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CmsSectionTranslation::class, 'cms_section_id');
    }

    /**
     * Get all items for the section
     */
    public function items(): HasMany
    {
        return $this->hasMany(CmsItem::class, 'cms_section_id');
    }

    /**
     * Get all links for the section (polymorphic)
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
     * Scope to get only active sections
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
     * Register media collections for sections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(\App\Support\Cms\CmsGalleryMedia::allMimeTypes());
    }

    /**
     * Register media conversions for sections
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonOptimized()
            ->nonQueued()
            ->performOnCollections('images', 'gallery');

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->nonOptimized()
            ->nonQueued()
            ->performOnCollections('images', 'gallery');
    }

    public static function normalizeType(?string $type): string
    {
        $type = strtolower(trim(str_replace('_', '-', (string) $type)));

        $aliases = [
            '' => 'default',
            'about' => 'about-us',
            'pricing' => 'plans',
            'pricing-plan' => 'plans',
            'project' => 'gallery',
            'testimonials' => 'testimonial',
            'about-us' => 'about-us',
            'about_us' => 'about-us',
            'whychooseus' => 'why-choose-us',
            'whychoose-us' => 'why-choose-us',
            'why_chooseus' => 'why-choose-us',
            'why_choose_us' => 'why-choose-us',
            'download' => 'download-app',
            'download-app' => 'download-app',
            'download_app' => 'download-app',
        ];

        return $aliases[$type] ?? $type;
    }

    public static function layoutCatalog(): array
    {
        return [
            'default' => [
                'default' => ['preview' => null],
            ],
            'hero' => [
                'style_1' => ['preview' => 'frontend/layouts/hero_section_style_1.png'],
            ],
            'features' => [
                'style_1' => ['preview' => 'frontend/layouts/features_section_style_1.png'],
            ],
            'about-us' => [
                'style_1' => ['preview' => 'frontend/layouts/about_section_style_1.png'],
            ],
            'services' => [
                'style_1' => ['preview' => 'frontend/layouts/services_section_style_1.png'],
                'style_2' => ['preview' => 'frontend/layouts/services_section_style_2.png'],
            ],
            'why-choose-us' => [
                'style_1' => ['preview' => 'frontend/layouts/why_choose_us_section_style_1.png'],
            ],
            'download-app' => [
                'style_1' => ['preview' => 'frontend/layouts/download_section_style_1.png'],
            ],
            'contact' => [
                'style_1' => ['preview' => 'frontend/layouts/contact_section_style_1.png'],
            ],
            'faq' => [
                'style_1' => ['preview' => 'frontend/layouts/faq_section_style_1.png'],
            ],
            'plans' => [
                'style_1' => ['preview' => 'frontend/layouts/plans_section_style_1.png'],
            ],
            'subscription' => [
                'default' => ['preview' => 'frontend/layouts/subscription_section.png'],
            ],
            'checkout' => [
                'default' => ['preview' => 'frontend/layouts/subscription_section.png'],
            ],
            'values' => [
                'style_1' => ['preview' => 'frontend/layouts/values_section_style_1.png'],
            ],
            'gallery' => [
                'default' => ['preview' => null],
            ],
            'testimonial' => [
                'default' => ['preview' => null],
            ],
            'content' => [
                'default' => ['preview' => null],
            ],
            'cta' => [
                'default' => ['preview' => null],
            ],
        ];
    }

    public static function layoutOptionsFor(?string $type): array
    {
        $type = static::normalizeType($type);
        $catalog = static::layoutCatalog();

        return $catalog[$type] ?? $catalog['default'];
    }

    public static function normalizeLayout(?string $layout, ?string $type): string
    {
        $layout = strtolower(trim((string) $layout));
        $options = static::layoutOptionsFor($type);

        if ($layout !== '' && array_key_exists($layout, $options)) {
            return $layout;
        }

        return array_key_first($options) ?? 'default';
    }

    public static function previewFor(?string $type, ?string $layout): ?string
    {
        $normalizedLayout = static::normalizeLayout($layout, $type);
        $options = static::layoutOptionsFor($type);

        return $options[$normalizedLayout]['preview'] ?? null;
    }

    public function syncPrimaryImageFromGallery(): void
    {
        $this->clearMediaCollection('images');

        $firstImage = $this->getMedia('gallery')->first(
            fn (Media $media) => CmsGalleryMedia::isImage($media)
        );

        if ($firstImage) {
            $firstImage->copy($this, 'images');
        }
    }
}
