<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;

class HomeController extends Controller
{
    public function index()
    {
        $cmsPage = CmsPage::query()
            ->where('slug', 'home')
            ->where('is_active', true)
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

        return view('frontend.pages.home.index', compact('cmsPage', 'cmsPageSections'));
    }
}
