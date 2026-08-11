@php
$raw = strtolower(trim((string) ($section->type ?? 'default')));
$type = str_replace('_', '-', $raw);
$typeToView = [
'faqs' => 'frontend.pages.faq.cms.types.faqs',
'default' => 'frontend.pages.faq.cms.types.generic',
];
$partial = $typeToView[$type] ?? 'frontend.pages.faq.cms.types.generic';
$layout = \App\Models\CmsSection::normalizeLayout($section->section_layout ?? null, $type);
$layoutCandidate = $partial . '_' . str_replace('-', '_', $layout);
if ($layout !== 'default' && view()->exists($layoutCandidate)) {
    $partial = $layoutCandidate;
}
@endphp
@include($partial, ['section' => $section])
