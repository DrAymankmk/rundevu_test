<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Clinic;
use App\Models\SitemapLink;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $urls = $this->buildUrls();

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    private function buildUrls(): array
    {
        $urls = [];

        if (! $this->hasSitemapLinksTable()) {
            return $urls;
        }

        $links = SitemapLink::query()->active()->ordered()->get();

        foreach ($links->where('link_type', SitemapLink::TYPE_PAGE) as $link) {
            $path = $link->normalizedPath();
            if (! $path) {
                continue;
            }

            $urls[] = $this->makeUrlEntry(
                $path,
                $link->effectiveLastmod(),
                $link->changefreq,
                number_format((float) $link->priority, 1, '.', ''),
                $link->include_alternates,
                [],
                $link->x_default_locale
            );
        }

        foreach ($links->where('link_type', SitemapLink::TYPE_GROUP) as $group) {
            $changefreq = $group->changefreq;
            $priority = number_format((float) $group->priority, 1, '.', '');
            $includeAlternates = $group->include_alternates;
            $xDefaultLocale = $group->x_default_locale;

            if ($group->group_key === 'blogs') {
                foreach ($this->publishedBlogPosts() as $post) {
                    $path = function_exists('frontend_blog_path')
                        ? frontend_blog_path($post->getRouteSlug(), frontend_default_locale())
                        : '/blog/' . ltrim((string) $post->getRouteSlug(), '/');

                    $overrides = [];
                    if ($includeAlternates) {
                        foreach ((array) config('app.locales', ['en', 'ar']) as $locale) {
                            $overrides[$locale] = frontend_url(
                                frontend_blog_path($post->getSlug($locale) ?: $post->getRouteSlug(), $locale),
                                [],
                                $locale
                            );
                        }
                    }

                    $urls[] = $this->makeUrlEntry(
                        $path,
                        $post->updated_at ?? $post->publish_date,
                        $changefreq,
                        $priority,
                        $includeAlternates,
                        $overrides,
                        $xDefaultLocale
                    );
                }
            }

            if ($group->group_key === 'clinics') {
                foreach ($this->publicClinics(1) as $clinic) {
                    $overrides = [];
                    if ($includeAlternates) {
                        foreach ((array) config('app.locales', ['en', 'ar']) as $locale) {
                            $overrides[$locale] = frontend_route('frontend.clinics.show', $clinic, true, $locale);
                        }
                    }

                    $urls[] = $this->makeUrlEntry(
                        '/clinics/' . $clinic->localizedSlug(frontend_default_locale()),
                        $clinic->updated_at,
                        $changefreq,
                        $priority,
                        $includeAlternates,
                        $overrides,
                        $xDefaultLocale
                    );
                }
            }

            if ($group->group_key === 'doctors') {
                foreach ($this->publicClinics(3) as $doctor) {
                    $overrides = [];
                    if ($includeAlternates) {
                        foreach ((array) config('app.locales', ['en', 'ar']) as $locale) {
                            $overrides[$locale] = frontend_route('frontend.doctors.show', $doctor, true, $locale);
                        }
                    }

                    $urls[] = $this->makeUrlEntry(
                        '/doctors/' . $doctor->localizedSlug(frontend_default_locale()),
                        $doctor->updated_at,
                        $changefreq,
                        $priority,
                        $includeAlternates,
                        $overrides,
                        $xDefaultLocale
                    );
                }
            }
        }

        return $urls;
    }

    private function makeUrlEntry(
        string $path,
        $lastmod,
        string $changefreq,
        string $priority,
        bool $includeAlternates = true,
        array $overrides = [],
        ?string $xDefaultLocale = null
    ): array {
        if (preg_match('/^https?:\/\//i', $path)) {
            $loc = $path;
            $alternates = [];
            if ($includeAlternates) {
                $alternates = ['x-default' => $loc];
                $alternates = $this->applyXDefault($alternates, $path, $overrides, $xDefaultLocale, true);
            }

            return [
                'loc' => $loc,
                'lastmod' => $this->toAtom($lastmod),
                'changefreq' => $changefreq,
                'priority' => $priority,
                'alternates' => $alternates,
            ];
        }

        if ($includeAlternates) {
            $alternates = function_exists('frontend_hreflang_urls')
                ? frontend_hreflang_urls($path, $overrides)
                : ['x-default' => url($path)];
            $alternates = $this->applyXDefault($alternates, $path, $overrides, $xDefaultLocale);
        } else {
            $alternates = [];
        }

        $defaultLocale = function_exists('frontend_default_locale') ? frontend_default_locale() : 'en';
        $loc = $alternates[$defaultLocale]
            ?? ($alternates['x-default'] ?? null)
            ?? (function_exists('frontend_url') ? frontend_url($path) : url($path));

        return [
            'loc' => $loc,
            'lastmod' => $this->toAtom($lastmod),
            'changefreq' => $changefreq,
            'priority' => $priority,
            'alternates' => $alternates,
        ];
    }

    private function applyXDefault(
        array $alternates,
        string $path,
        array $overrides,
        ?string $xDefaultLocale,
        bool $absoluteOnly = false
    ): array {
        $choice = strtolower(trim((string) $xDefaultLocale));
        if ($choice === '' || $choice === SitemapLink::X_DEFAULT_AUTO) {
            return $alternates;
        }

        if ($choice === SitemapLink::X_DEFAULT_OMIT) {
            unset($alternates['x-default']);

            return $alternates;
        }

        if ($absoluteOnly) {
            return $alternates;
        }

        if (isset($alternates[$choice])) {
            $alternates['x-default'] = $alternates[$choice];
        } elseif (isset($overrides[$choice])) {
            $alternates['x-default'] = $overrides[$choice];
        } elseif (function_exists('frontend_url')) {
            $alternates['x-default'] = frontend_url($path, [], $choice);
        }

        return $alternates;
    }

    private function toAtom($date): ?string
    {
        if (! $date) {
            return null;
        }

        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $carbon->toAtomString();
    }

    private function hasSitemapLinksTable(): bool
    {
        try {
            return Schema::hasTable('sitemap_links');
        } catch (Throwable $exception) {
            return false;
        }
    }

    private function publishedBlogPosts()
    {
        try {
            if (! Schema::hasTable('blog_posts')) {
                return collect();
            }

            return BlogPost::query()
                ->published()
                ->with('translations')
                ->orderByDesc('publish_date')
                ->orderByDesc('id')
                ->limit(500)
                ->get();
        } catch (Throwable $exception) {
            return collect();
        }
    }

    private function publicClinics(int $appType)
    {
        try {
            if (! Schema::hasTable('clinics')) {
                return collect();
            }

            return Clinic::query()
                ->where('status', 1)
                ->where('app_type', $appType)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->whereNotNull('slug')->where('slug', '!=', '');
                    })->orWhere(function ($q) {
                        $q->whereNotNull('slug_en')->where('slug_en', '!=', '');
                    });
                })
                ->get(['slug', 'slug_en', 'updated_at']);
        } catch (Throwable $exception) {
            return collect();
        }
    }
}
