<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Services\Seo\SeoResolver;

class ServiceController extends Controller
{
    public function __construct(private SeoResolver $seoResolver)
    {
    }

    public function index()
    {
        $cmsPage = CmsPage::query()
            ->where('slug', 'services')
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

        $seo = $this->seoResolver->resolveForCmsSlug('services', 'frontend.services', __('main.services'));

        return view('frontend.pages.services.index', compact('cmsPage', 'cmsPageSections', 'seo'));
    }
}
