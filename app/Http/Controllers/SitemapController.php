<?php

namespace App\Http\Controllers;

use App\Models\CmsItem;
use App\Models\CmsPage;
use App\Models\CmsSection;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
        'social-media' => '/social-media',
    ];

    public function __invoke()
    {
        $urls = $this->buildUrls();

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }

    private function buildUrls(): array
    {
        $baseUrl = rtrim(request()->getSchemeAndHttpHost(), '/');
        $pages = CmsPage::query()
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

        $urls = [];

        foreach (self::CMS_ROUTES as $slug => $path) {
            $page = $pages->get($slug);

            $urls[] = [
                'loc' => $baseUrl . $path,
                'lastmod' => $page ? $this->pageLastModified($page)->toAtomString() : null,
                'changefreq' => $slug === 'home' ? 'daily' : 'weekly',
                'priority' => $slug === 'home' ? '1.0' : '0.8',
            ];
        }

        foreach (self::STATIC_ROUTES as $path) {
            $urls[] = [
                'loc' => $baseUrl . $path,
                'lastmod' => null,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return $urls;
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
