<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Services\Seo\SeoResolver;

class HomeController extends Controller
{
    public function __construct(private SeoResolver $seoResolver)
    {
    }

    public function index()
    {
        $cmsPage = CmsPage::query()
            ->where('slug', 'home')
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

        $seo = $this->seoResolver->resolveForCmsSlug('home', 'frontend.home');
        $heroSection = $cmsPageSections->first(function ($section) {
            $type = strtolower((string) ($section->type ?? ''));

            return $type === 'hero' || str_contains((string) ($section->slug ?? ''), 'hero');
        });
        $seo['lcp_image'] = $heroSection
            ? $heroSection->getMediaUrl('images', app()->getLocale(), asset('frontend/assets/img/hero/hero_bg_1_1.jpg'), true)
            : asset('frontend/assets/img/hero/hero_bg_1_1.jpg');

        return view('frontend.pages.home.index', compact('cmsPage', 'cmsPageSections', 'seo'));
    }
}
