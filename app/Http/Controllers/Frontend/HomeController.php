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
        $seo['lcp_image'] = $heroSection
            ? $heroSection->getMediaUrl('images', app()->getLocale(), asset('frontend/assets/img/hero/hero_bg_1_1.jpg'), true)
            : asset('frontend/assets/img/hero/hero_bg_1_1.jpg');

        $specialties = FrontendContentCache::specialties();

        return view('frontend.pages.home.index', compact('cmsPage', 'cmsPageSections', 'seo', 'specialties'));
    }
}
