<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Clinic;
use App\Models\CmsItem;
use App\Models\CmsPage;
use App\Models\CmsSection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class SitemapController extends Controller
{
    private const CMS_ROUTES = [
        'home' => '/',
        'about' => '/about',
        'services' => '/services',
        'faq' => '/faq',
        'subscription' => '/subscription',
        'contact' => '/contact',
    ];

    private const STATIC_ROUTES = [
        'blog' => '/blog',
        'clinics' => '/clinics',
        'doctors' => '/doctors',
        'social-media' => '/social-media',
    ];

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
        $pages = $this->cmsPages();
        $urls = [];

        foreach (self::CMS_ROUTES as $slug => $path) {
            $page = $pages->get($slug);
            $urls[] = $this->localizedUrl(
                $path,
                $page ? $this->pageLastModified($page) : now(),
                $slug === 'home' ? 'daily' : 'weekly',
                $slug === 'home' ? '1.0' : '0.8'
            );
        }

        foreach (self::STATIC_ROUTES as $path) {
            $urls[] = $this->localizedUrl($path, now(), 'weekly', '0.7');
        }

        foreach ($this->publishedBlogPosts() as $post) {
            $path = function_exists('frontend_blog_path')
                ? frontend_blog_path($post->getRouteSlug(), frontend_default_locale())
                : '/blog/' . ltrim((string) $post->getRouteSlug(), '/');
            $overrides = [];
            foreach ((array) config('app.locales', ['en', 'ar']) as $locale) {
                $overrides[$locale] = frontend_url(frontend_blog_path($post->getSlug($locale) ?: $post->getRouteSlug(), $locale), [], $locale);
            }
            $urls[] = $this->localizedUrl($path, $post->updated_at ?? $post->publish_date, 'weekly', '0.6', $overrides);
        }

        foreach ($this->publicClinics(1) as $clinic) {
            $urls[] = $this->localizedUrl('/clinics/' . $clinic->slug, $clinic->updated_at, 'weekly', '0.6');
        }

        foreach ($this->publicClinics(3) as $doctor) {
            $urls[] = $this->localizedUrl('/doctors/' . $doctor->slug, $doctor->updated_at, 'weekly', '0.6');
        }

        return $urls;
    }

    private function localizedUrl(string $path, $lastmod, string $changefreq, string $priority, array $overrides = []): array
    {
        $alternates = function_exists('frontend_hreflang_urls')
            ? frontend_hreflang_urls($path, $overrides)
            : ['x-default' => url($path)];

        $defaultLocale = function_exists('frontend_default_locale') ? frontend_default_locale() : 'en';

        return [
            'loc' => $alternates[$defaultLocale] ?? reset($alternates),
            'lastmod' => $this->toAtom($lastmod),
            'changefreq' => $changefreq,
            'priority' => $priority,
            'alternates' => $alternates,
        ];
    }

    private function toAtom($date): ?string
    {
        if (!$date) {
            return null;
        }

        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $carbon->toAtomString();
    }

    private function cmsPages()
    {
        try {
            if (!Schema::hasTable('cms_pages')) {
                return collect();
            }

            return CmsPage::query()
                ->active()
                ->whereIn('slug', array_keys(self::CMS_ROUTES))
                ->with([
                    'sections' => static function ($query) {
                        $query->active()->with([
                            'items' => static function ($itemsQuery) {
                                $itemsQuery->active();
                            },
                        ]);
                    },
                ])
                ->get()
                ->keyBy('slug');
        } catch (Throwable $exception) {
            return collect();
        }
    }

    private function publishedBlogPosts()
    {
        try {
            if (!Schema::hasTable('blog_posts')) {
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
            if (!Schema::hasTable('clinics')) {
                return collect();
            }

            return Clinic::query()
                ->where('status', 1)
                ->where('app_type', $appType)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->get(['slug', 'updated_at']);
        } catch (Throwable $exception) {
            return collect();
        }
    }

    private function pageLastModified(CmsPage $page): Carbon
    {
        $dates = collect([$page->updated_at]);
        $sectionIds = $page->sections->pluck('id')->all();
        $itemIds = $page->sections->flatMap->items->pluck('id')->all();

        $dates = $dates
            ->merge($page->sections->pluck('updated_at'))
            ->merge($page->sections->flatMap->items->pluck('updated_at'))
            ->merge($this->mediaUpdatedDates(CmsSection::class, $sectionIds))
            ->merge($this->mediaUpdatedDates(CmsItem::class, $itemIds))
            ->filter();

        return $dates
            ->map(static fn ($date) => $date instanceof Carbon ? $date : Carbon::parse($date))
            ->sortDesc()
            ->first() ?? now();
    }

    private function mediaUpdatedDates(string $modelType, array $modelIds)
    {
        if ($modelIds === []) {
            return collect();
        }

        return Media::query()
            ->where('model_type', $modelType)
            ->whereIn('model_id', $modelIds)
            ->pluck('updated_at');
    }
}
