<?php

namespace App\Services\Frontend;

use App\Models\CmsPage;
use App\Models\Package;
use App\Models\Specialty;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FrontendContentCache
{
    public const TTL_SECONDS = 3600;

    public const KEY_SPECIALTIES = 'frontend.specialties';
    public const KEY_PACKAGES = 'frontend.packages.active';
    public const KEY_CMS_PREFIX = 'frontend.cms.page.';

    public static function specialties(): Collection
    {
        $locale = app()->getLocale();

        return Cache::remember(
            self::KEY_SPECIALTIES . '.' . $locale,
            self::TTL_SECONDS,
            static function () use ($locale) {
                return Specialty::assignFrontendSlugs(
                    Specialty::query()
                        ->where('status', 1)
                        ->whereNull('parent_id')
                        ->orderBy($locale === 'en' ? 'name_en' : 'name_ar')
                        ->get()
                );
            }
        );
    }

    public static function packages(): Collection
    {
        return Cache::remember(
            self::KEY_PACKAGES,
            self::TTL_SECONDS,
            static function () {
                return Package::query()
                    ->where('status', 1)
                    ->orderBy('price')
                    ->get();
            }
        );
    }

    /**
     * @return array{page: ?CmsPage, sections: Collection}
     */
    public static function cmsPageWithSections(string $slug): array
    {
        $locale = app()->getLocale();
        $key = self::KEY_CMS_PREFIX . $slug . '.' . $locale;

        return Cache::remember($key, self::TTL_SECONDS, static function () use ($slug) {
            $cmsPage = CmsPage::query()
                ->where('slug', $slug)
                ->where('is_active', true)
                ->with(['seoMeta.translations', 'translations'])
                ->first();

            $cmsPageSections = collect();
            if ($cmsPage) {
                $cmsPageSections = $cmsPage->sections()
                    ->active()
                    ->ordered()
                    ->with([
                        'translations',
                        'links' => static function ($q) {
                            $q->active()->ordered()->with('translations');
                        },
                        'items' => static function ($q) {
                            $q->active()->ordered()->with([
                                'translations',
                                'links' => static function ($lq) {
                                    $lq->active()->ordered()->with('translations');
                                },
                            ]);
                        },
                    ])
                    ->get();
            }

            return [
                'page' => $cmsPage,
                'sections' => $cmsPageSections,
            ];
        });
    }

    public static function flushSpecialties(): void
    {
        foreach (self::locales() as $locale) {
            Cache::forget(self::KEY_SPECIALTIES . '.' . $locale);
        }
    }

    public static function flushPackages(): void
    {
        Cache::forget(self::KEY_PACKAGES);
    }

    public static function flushCms(?string $slug = null): void
    {
        $slugs = $slug !== null && $slug !== ''
            ? [$slug]
            : ['home', 'subscription', 'about', 'services', 'faq', 'contact', 'blog'];

        foreach ($slugs as $pageSlug) {
            foreach (self::locales() as $locale) {
                Cache::forget(self::KEY_CMS_PREFIX . $pageSlug . '.' . $locale);
            }
        }
    }

    public static function flushAll(): void
    {
        self::flushSpecialties();
        self::flushPackages();
        self::flushCms();
    }

    /**
     * @return array<int, string>
     */
    private static function locales(): array
    {
        $allowed = config('app.locales');
        if (! is_array($allowed) || $allowed === []) {
            return ['en', 'ar'];
        }

        return array_values(array_unique(array_map('strval', $allowed)));
    }
}
