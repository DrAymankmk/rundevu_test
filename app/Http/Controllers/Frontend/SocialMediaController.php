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
        $platforms = collect(config('social.platforms', []))
            ->filter(static function ($platform) {
                return filled($platform['url'] ?? null);
            })
            ->values();

        $seo = $this->seoResolver->defaults([
            'title' => __('main.social_media'),
            'canonical' => route('frontend.social'),
        ]);

        return view('frontend.pages.social.index', compact('platforms', 'seo'));
    }
}
