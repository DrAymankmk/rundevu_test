<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitemapLink extends Model
{
    public const TYPE_PAGE = 'page';
    public const TYPE_GROUP = 'group';

    public const CHANGEFREQ_OPTIONS = [
        'always',
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'yearly',
        'never',
    ];

    public const GROUP_KEYS = [
        'blogs',
        'clinics',
        'doctors',
    ];

    public const X_DEFAULT_AUTO = 'auto';
    public const X_DEFAULT_OMIT = 'omit';

    protected $table = 'sitemap_links';

    protected $fillable = [
        'title',
        'link_type',
        'group_key',
        'path',
        'changefreq',
        'priority',
        'lastmod',
        'include_alternates',
        'x_default_locale',
        'is_active',
        'sort_order',
    ];

    public static function xDefaultOptions(): array
    {
        $options = [
            self::X_DEFAULT_AUTO => __('sitemap_links.x_default_auto'),
            self::X_DEFAULT_OMIT => __('sitemap_links.x_default_omit'),
        ];

        foreach ((array) config('app.locales', ['en', 'ar']) as $locale) {
            $options[$locale] = strtoupper((string) $locale);
        }

        return $options;
    }

    protected $casts = [
        'priority' => 'float',
        'lastmod' => 'datetime',
        'include_alternates' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePages($query)
    {
        return $query->where('link_type', self::TYPE_PAGE);
    }

    public function scopeGroups($query)
    {
        return $query->where('link_type', self::TYPE_GROUP);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function isGroup(): bool
    {
        return $this->link_type === self::TYPE_GROUP;
    }

    public function isPage(): bool
    {
        return $this->link_type === self::TYPE_PAGE;
    }

    public function normalizedPath(): ?string
    {
        if ($this->path === null || $this->path === '') {
            return null;
        }

        $path = trim($this->path);

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        return '/' . ltrim($path, '/');
    }

    public function effectiveLastmod()
    {
        return $this->lastmod ?: $this->updated_at;
    }
}
