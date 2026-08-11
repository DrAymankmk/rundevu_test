@php
    $raw = strtolower(trim((string) ($section->type ?? 'default')));
    $type = str_replace('_', '-', $raw);
    $subscriptionFormView = 'frontend.pages.subscription.cms.types.subscription';
    $typeToView = [
        'subscription' => $subscriptionFormView,
        'checkout' => $subscriptionFormView,
        'default' => 'frontend.pages.subscription.cms.types.generic',
    ];
    $partial = $typeToView[$type] ?? 'frontend.pages.subscription.cms.types.generic';
    $layout = \App\Models\CmsSection::normalizeLayout($section->section_layout ?? null, $type);
    $layoutCandidate = $partial . '_' . str_replace('-', '_', $layout);
    if ($layout !== 'default' && view()->exists($layoutCandidate)) {
        $partial = $layoutCandidate;
    }
@endphp
@include($partial, [
'section' => $section,
'packages' => $packages ?? collect(),
'selectedPackage' => $selectedPackage ?? null,
'selectedPackageId' => $selectedPackageId ?? null,
])
