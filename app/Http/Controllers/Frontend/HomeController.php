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

        return view('frontend.pages.home.index', compact('cmsPage', 'cmsPageSections', 'seo'));
    }
}
