<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\FrontendContentCache;
use App\Services\Seo\SeoResolver;

class HomeController extends Controller
{
    public function __construct(private SeoResolver $seoResolver)
    {
    }

    public function index()
    {
        $cached = FrontendContentCache::cmsPageWithSections('home');
        $cmsPage = $cached['page'];
        $cmsPageSections = $cached['sections'];

        $seo = $this->seoResolver->resolveForCmsSlug('home', 'frontend.home');
        $heroSection = $cmsPageSections->first(function ($section) {
            $type = strtolower((string) ($section->type ?? ''));

            return $type === 'hero' || str_contains((string) ($section->slug ?? ''), 'hero');
        });
        $defaultHeroBg = asset('frontend/assets/img/hero/hero_bg_1_1.jpg');
        $seo['lcp_image'] = $heroSection
            ? $heroSection->getMediaUrl('images', app()->getLocale(), $defaultHeroBg, true)
            : $defaultHeroBg;
        // Keep the preload in sync with the hero <img srcset> so phones do not fetch both sizes.
        $heroSmall = $heroSection
            ? $heroSection->getMediaUrl('images', app()->getLocale(), $defaultHeroBg, true, 'preview_webp')
            : null;
        $seo['lcp_image_srcset'] = $heroSmall && $heroSmall !== $seo['lcp_image']
            ? $heroSmall . ' 1200w, ' . $seo['lcp_image'] . ' 1920w'
            : null;

        $specialties = FrontendContentCache::specialties();

        return view('frontend.pages.home.index', compact('cmsPage', 'cmsPageSections', 'seo', 'specialties'));
    }
}
