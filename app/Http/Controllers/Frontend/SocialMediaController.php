<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class SocialMediaController extends Controller
{
    public function index()
    {
        $platforms = collect(config('social.platforms', []))
            ->filter(static function ($platform) {
                return filled($platform['url'] ?? null);
            })
            ->values();

        return view('frontend.pages.social.index', compact('platforms'));
    }
}
