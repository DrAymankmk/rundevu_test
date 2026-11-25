<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Clinic;
use App\Models\SitemapLink;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $locales = $this->supportedLocales();
        $sitemaps = [];

        foreach ($locales as $locale) {
            $sitemaps[] = [
                'loc' => url('/sitemap-' . $locale . '.xml'),
                'lastmod' => now()->toAtomString(),
            ];
        }

        $xml = view('sitemap-index', compact('sitemaps'))->render();

        return $this->xmlResponse($xml);
    }

    public function locale(string $locale): Response
    {
        $locale = strtolower($locale);
        abort_unless(in_array($locale, $this->supportedLocales(), true), 404);

        $urls = $this->buildUrls($locale);
        $xml = view('sitemap', compact('urls'))->render();

        return $this->xmlResponse($xml);
    }

    public function urlCount(string $locale): int
    {
        $locale = strtolower($locale);
        if (! in_array($locale, $this->supportedLocales(), true)) {
            return 0;
        }

        return count($this->buildUrls($locale));
    }

    private function xmlResponse(string $xml): Response
    {
        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * @return array<int, string>
     */
    private function supportedLocales(): array
    {
        $locales = config('app.locales');
        if (! is_array($locales) || $locales === []) {
            return ['en', 'ar'];
        }

        return array_values(array_unique(array_map('strval', $locales)));
    }

    private function buildUrls(string $locale): array
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
                $link->x_default_locale,
                $locale
            );
        }

        foreach ($links->where('link_type', SitemapLink::TYPE_GROUP) as $group) {
            $changefreq = $group->changefreq;
            $priority = number_format((float) $group->priority, 1, '.', '');
            $includeAlternates = $group->include_alternates;
            $xDefaultLocale = $group->x_default_locale;

            if ($group->group_key === 'blogs') {
                foreach ($this->publishedBlogPosts() as $post) {
                    $overrides = [];
                    foreach ($this->supportedLocales() as $itemLocale) {
                        $overrides[$itemLocale] = frontend_url(
                            frontend_blog_path($post->getSlug($itemLocale) ?: $post->getRouteSlug(), $itemLocale),
                            [],
                            $itemLocale
                        );
                    }

                    $path = function_exists('frontend_blog_path')
                        ? frontend_blog_path($post->getSlug($locale) ?: $post->getRouteSlug(), $locale)
                        : '/blog/' . ltrim((string) ($post->getSlug($locale) ?: $post->getRouteSlug()), '/');

                    $urls[] = $this->makeUrlEntry(
                        $path,
                        $post->updated_at ?? $post->publish_date,
                        $changefreq,
                        $priority,
                        $includeAlternates,
                        $overrides,
                        $xDefaultLocale,
                        $locale
                    );
                }
            }

            if ($group->group_key === 'clinics') {
                foreach ($this->publicClinics(1) as $clinic) {
                    $overrides = [];
                    foreach ($this->supportedLocales() as $itemLocale) {
                        $overrides[$itemLocale] = frontend_route('frontend.clinics.show', $clinic, true, $itemLocale);
                    }

                    $urls[] = $this->makeUrlEntry(
                        '/clinics/' . $clinic->localizedSlug($locale),
                        $clinic->updated_at,
                        $changefreq,
                        $priority,
                        $includeAlternates,
                        $overrides,
                        $xDefaultLocale,
                        $locale
                    );
                }
            }

            if ($group->group_key === 'doctors') {
                foreach ($this->publicClinics(3) as $doctor) {
                    $overrides = [];
                    foreach ($this->supportedLocales() as $itemLocale) {
                        $overrides[$itemLocale] = frontend_route('frontend.doctors.show', $doctor, true, $itemLocale);
                    }

                    $urls[] = $this->makeUrlEntry(
                        '/doctors/' . $doctor->localizedSlug($locale),
                        $doctor->updated_at,
                        $changefreq,
                        $priority,
                        $includeAlternates,
                        $overrides,
                        $xDefaultLocale,
                        $locale
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
        ?string $xDefaultLocale = null,
        string $locLocale = 'en'
    ): array {
        if (preg_match('/^https?:\/\//i', $path)) {
            $loc = $path;
            $alternates = [];
            if ($includeAlternates) {
                foreach ($this->supportedLocales() as $locale) {
                    $alternates[$locale] = $loc;
                }
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

        $normalizedPath = function_exists('frontend_strip_locale_prefix')
            ? frontend_strip_locale_prefix($path)
            : $path;

        if ($includeAlternates) {
            $alternates = function_exists('frontend_hreflang_urls')
                ? frontend_hreflang_urls($normalizedPath, $overrides)
                : ['x-default' => url($normalizedPath)];
            $alternates = $this->applyXDefault($alternates, $normalizedPath, $overrides, $xDefaultLocale);
        } else {
            $alternates = [];
        }

        $loc = $overrides[$locLocale]
            ?? ($alternates[$locLocale] ?? null)
            ?? (function_exists('frontend_url') ? frontend_url($normalizedPath, [], $locLocale) : url($normalizedPath));

        // Keep a stable alternate order: locales first, then x-default.
        if ($includeAlternates && $alternates !== []) {
            $ordered = [];
            foreach ($this->supportedLocales() as $locale) {
                if (isset($alternates[$locale])) {
                    $ordered[$locale] = $alternates[$locale];
                }
            }
            if (isset($alternates['x-default'])) {
                $ordered['x-default'] = $alternates['x-default'];
            }
            $alternates = $ordered;
        }

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
            $defaultLocale = function_exists('frontend_default_locale') ? frontend_default_locale() : 'en';
            if (isset($alternates[$defaultLocale])) {
                $alternates['x-default'] = $alternates[$defaultLocale];
            } elseif (isset($overrides[$defaultLocale])) {
                $alternates['x-default'] = $overrides[$defaultLocale];
            } elseif (! $absoluteOnly && function_exists('frontend_url')) {
                $alternates['x-default'] = frontend_url($path, [], $defaultLocale);
            }

            return $alternates;
        }

        if ($choice === SitemapLink::X_DEFAULT_OMIT) {
            unset($alternates['x-default']);

            return $alternates;
        }

        if ($absoluteOnly) {
            if (! isset($alternates['x-default']) && isset($alternates[$choice])) {
                $alternates['x-default'] = $alternates[$choice];
            }

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
