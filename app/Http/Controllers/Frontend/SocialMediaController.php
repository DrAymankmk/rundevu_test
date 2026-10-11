<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Seo\SeoResolver;

class SocialMediaController extends Controller
{
    public function __construct(private SeoResolver $seoResolver)
    {
    }

    public function index()
    {
        $platforms = website_social_platforms();
        $storePlatforms = website_store_platforms();

        $seo = $this->seoResolver->defaults([
            'title' => __('main.social_media'),
            'description' => __('main.social_media_page_subtitle'),
            'canonical' => frontend_route('frontend.social'),
            'lcp_image' => frontend_breadcrumb_image('social'),
        ]);

        return view('frontend.pages.social.index', compact('platforms', 'storePlatforms', 'seo'));
    }
}
