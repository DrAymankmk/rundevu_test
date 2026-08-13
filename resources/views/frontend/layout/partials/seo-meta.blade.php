@php
    $seo = $seo ?? [];
@endphp

<title>{{ $seo['title'] ?? config('app.name') }}</title>
<meta name="description" content="{{ $seo['description'] ?? '' }}">
@if(!empty($seo['keywords']))
<meta name="keywords" content="{{ $seo['keywords'] }}">
@endif
<meta name="robots" content="{{ $seo['robots'] ?? 'index,follow' }}">
<link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">

<meta property="og:title" content="{{ $seo['og']['title'] ?? $seo['title'] ?? config('app.name') }}">
<meta property="og:description" content="{{ $seo['og']['description'] ?? $seo['description'] ?? '' }}">
<meta property="og:image" content="{{ $seo['og']['image'] ?? asset('frontend/assets/img/logo.png') }}">
<meta property="og:url" content="{{ $seo['og']['url'] ?? $seo['canonical'] ?? url()->current() }}">
<meta property="og:type" content="{{ $seo['og']['type'] ?? 'website' }}">

<meta name="twitter:card" content="{{ $seo['twitter']['card'] ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $seo['twitter']['title'] ?? $seo['title'] ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $seo['twitter']['description'] ?? $seo['description'] ?? '' }}">
<meta name="twitter:image" content="{{ $seo['twitter']['image'] ?? $seo['og']['image'] ?? asset('frontend/assets/img/logo.png') }}">

@if(!empty($seo['schema_json']))
<script type="application/ld+json">{!! json_encode($seo['schema_json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif
