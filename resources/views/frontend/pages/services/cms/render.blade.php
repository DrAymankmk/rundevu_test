@php
    $raw = strtolower(trim((string) ($section->type ?? 'default')));
    $type = str_replace('_', '-', $raw);
    $typeToView = [
         'services' => 'frontend.pages.services.cms.types.services',
         'default' => 'frontend.pages.services.cms.types.generic',
    ];
    $partial = $typeToView[$type] ?? 'frontend.pages.services.cms.types.generic';
    $layout = \App\Models\CmsSection::normalizeLayout($section->section_layout ?? null, $type);
    $layoutCandidate = $partial . '_' . str_replace('-', '_', $layout);
    if ($layout !== 'default' && view()->exists($layoutCandidate)) {
        $partial = $layoutCandidate;
    }
@endphp
@include($partial, ['section' => $section])
