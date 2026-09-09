<?php

namespace App\Models;

use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogPost extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasSeo;

    protected $table = 'blog_posts';

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'publish_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'publish_date' => 'datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(BlogPostTranslation::class, 'blog_post_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_post', 'blog_post_id', 'blog_category_id')
            ->withTimestamps();
    }

    public function translation(?string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations()->where('locale', $locale)->first();
    }

    public function getTranslatedAttribute(string $attribute, ?string $locale = null, ?string $fallbackLocale = null)
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('publish_date')
                    ->orWhere('publish_date', '<=', now());
            });
    }

    public function scopeWhereSlug(Builder $query, string $slug): Builder
    {
        return $query->where(function (Builder $q) use ($slug) {
            $q->where('slug', $slug)
                ->orWhereHas('translations', function (Builder $translations) use ($slug) {
                    $translations->where('slug', $slug);
                });
        });
    }

    public function getSlug(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $translation = $this->translation($locale);

        if ($translation && filled($translation->slug)) {
            return $translation->slug;
        }

        $fallback = $this->translations->first(fn ($row) => filled($row->slug));

        return filled($fallback?->slug) ? $fallback->slug : (string) $this->slug;
    }

    public static function imageCollection(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return 'image_' . $locale;
    }

    public static function makeSlug(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        $slug = Str::slug($value, '-', null);

        return $slug !== '' ? $slug : Str::slug($value);
    }

    public static function uniqueSlug(string $slug, ?int $ignorePostId = null): string
    {
        $slug = trim($slug, '-');
        if ($slug === '') {
            $slug = 'post';
        }

        $base = $slug;
        $i = 2;

        while (static::slugExists($slug, $ignorePostId)) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public static function slugExists(string $slug, ?int $ignorePostId = null): bool
    {
        $translationQuery = BlogPostTranslation::query()->where('slug', $slug);
        $postQuery = static::query()->where('slug', $slug);

        if ($ignorePostId) {
            $translationQuery->where('blog_post_id', '!=', $ignorePostId);
            $postQuery->where('id', '!=', $ignorePostId);
        }

        return $translationQuery->exists() || $postQuery->exists();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonOptimized()
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->nonOptimized()
            ->nonQueued();
    }

    public function getImageMedia(?string $locale = null): ?Media
    {
        $locale = $locale ?? app()->getLocale();

        return $this->getFirstMedia(static::imageCollection($locale))
            ?: $this->getFirstMedia('image');
    }

    public function getImageUrl(string $conversion = '', ?string $locale = null): ?string
    {
        $media = $this->getImageMedia($locale);
        if (!$media) {
            return null;
        }

        if ($conversion !== '' && $media->hasGeneratedConversion($conversion)) {
            return $media->getUrl($conversion);
        }

        return $media->getUrl();
    }

    public function clearAllMedia(): void
    {
        $this->getMedia()
            ->pluck('collection_name')
            ->unique()
            ->each(fn ($collection) => $this->clearMediaCollection($collection));
    }
}
