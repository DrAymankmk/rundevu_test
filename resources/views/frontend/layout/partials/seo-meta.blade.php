@php
    $seo = $seo ?? [];
    $siteName = $seo['site_name'] ?? config('app.name', 'Randevu');
    $title = $seo['title'] ?? $siteName;
    $description = $seo['description'] ?? '';
    $canonical = $seo['canonical'] ?? url()->current();
    $ogLocale = $seo['og_locale'] ?? (app()->getLocale() === 'ar' ? 'ar_SA' : 'en_US');
    $ogImage = $seo['og']['image'] ?? asset('frontend/assets/img/logo.png');
    $schema = $seo['schema_json'] ?? null;
    if (is_string($schema) && $schema !== '') {
        $decoded = json_decode($schema, true);
        $schema = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }
@endphp

<title>{{ $title }}</title>
@if($description !== '')
<meta name="description" content="{{ $description }}">
@endif
@if(!empty($seo['keywords']))
<meta name="keywords" content="{{ $seo['keywords'] }}">
@endif
<meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
<link rel="canonical" href="{{ $canonical }}">
@foreach(($seo['hreflang'] ?? []) as $hreflang => $href)
<link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $href }}">
@endforeach

<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $ogLocale }}">
@foreach(($seo['og_locale_alternates'] ?? []) as $alternateLocale)
<meta property="og:locale:alternate" content="{{ $alternateLocale }}">
@endforeach
<meta property="og:title" content="{{ $seo['og']['title'] ?? $title }}">
<meta property="og:description" content="{{ $seo['og']['description'] ?? $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:url" content="{{ $seo['og']['url'] ?? $canonical }}">
<meta property="og:type" content="{{ $seo['og']['type'] ?? 'website' }}">

<meta name="twitter:card" content="{{ $seo['twitter']['card'] ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $seo['twitter']['title'] ?? $title }}">
<meta name="twitter:description" content="{{ $seo['twitter']['description'] ?? $description }}">
<meta name="twitter:image" content="{{ $seo['twitter']['image'] ?? $ogImage }}">
<meta name="twitter:image:alt" content="{{ $seo['twitter']['image_alt'] ?? $title }}">

@if(!empty($schema))
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif
