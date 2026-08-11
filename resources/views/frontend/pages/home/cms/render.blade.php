@php
    $raw = strtolower(trim((string) ($section->type ?? 'default')));
    $type = str_replace('_', '-', $raw);
    $typeToView = [
        'hero' => 'frontend.pages.home.cms.types.hero',
        'features' => 'frontend.pages.home.cms.types.features',
        'about-us' => 'frontend.pages.home.cms.types.about_us',
        'services' => 'frontend.pages.home.cms.types.services',
        'why-choose-us' => 'frontend.pages.home.cms.types.why_choose_us',
        'download-app' => 'frontend.pages.home.cms.types.download_app',
        'gallery' => 'frontend.pages.home.cms.types.gallery',
        'testimonial' => 'frontend.pages.home.cms.types.testimonial',
        'testimonials' => 'frontend.pages.home.cms.types.testimonial',
        'plans' => 'frontend.pages.home.cms.types.plans',
        'pricing' => 'frontend.pages.home.cms.types.plans',
        'pricing-plan' => 'frontend.pages.home.cms.types.plans',
        'project' => 'frontend.pages.home.cms.types.gallery',
        'text' => 'frontend.pages.home.cms.types.generic',
        'default' => 'frontend.pages.home.cms.types.generic',
    ];
    $partial = $typeToView[$type] ?? 'frontend.pages.home.cms.types.generic';
    $layout = \App\Models\CmsSection::normalizeLayout($section->section_layout ?? null, $type);
    $layoutCandidate = $partial . '_' . str_replace('-', '_', $layout);
    if ($layout !== 'default' && view()->exists($layoutCandidate)) {
        $partial = $layoutCandidate;
    }
@endphp
@include($partial, ['section' => $section])
