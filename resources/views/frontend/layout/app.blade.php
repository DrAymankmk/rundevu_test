<!doctype html>
@php
$sessionLang = session('lang');
$effectiveLocale = $sessionLang !== null && $sessionLang !== ''
	? (string) $sessionLang
	: app()->getLocale();
$effectiveLocale = strtolower(trim(str_replace('_', '-', $effectiveLocale)));
if ($effectiveLocale === '') {
	$effectiveLocale = strtolower((string) config('app.locale', 'en'));
}
$htmlLang = explode('-', $effectiveLocale)[0] ?: $effectiveLocale;
$rtlLangs = ['ar', 'fa', 'he', 'ur'];
$isRtl = in_array($htmlLang, $rtlLangs, true);
$frontendCss = static function (string $file): string {
	$relative = 'frontend/assets/css/' . ltrim($file, '/');
	$path = public_path($relative);
	$version = is_file($path) ? (string) filemtime($path) : '1';

	return asset($relative) . '?v=' . $version;
};
$frontendJs = static function (string $file): string {
	$relative = 'frontend/assets/js/' . ltrim($file, '/');
	$path = public_path($relative);
	$version = is_file($path) ? (string) filemtime($path) : '1';

	return asset($relative) . '?v=' . $version;
};
$logoPngUrl = frontend_logo_url('png');
$logoWebpUrl = is_file(public_path('frontend/assets/img/logo.webp')) ? frontend_logo_url('webp') : null;
$logoPreloadUrl = $logoWebpUrl ?: $logoPngUrl;
$themeCss = $isRtl ? $frontendCss('rtl_style.min.css') : $frontendCss('style.min.css');
$fontUrl = 'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&family=Outfit:wght@400;500;600;700&family=Saira:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap';
$lcpImage = $seo['lcp_image'] ?? null;
$deferCss = static function (string $href): string {
	return '<link rel="stylesheet" href="' . e($href) . '" media="print" onload="this.media=\'all\'">'
		. '<noscript><link rel="stylesheet" href="' . e($href) . '"></noscript>';
};
@endphp
<html class="no-js" lang="{{ $htmlLang }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@include('frontend.layout.partials.seo-meta', ['seo' => $seo ?? []])
	@include('frontend.layout.partials.google-tags')
	<meta name="author" content="{{ config('app.name', 'Randevu') }}">
	<meta name="theme-color" content="#3E66F3">
	<meta name="format-detection" content="telephone=no">

	<link rel="icon" type="image/png" href="{{ $logoPngUrl }}">
	<link rel="apple-touch-icon" href="{{ $logoPngUrl }}">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preload" as="style" href="{{ $fontUrl }}">
	<link rel="stylesheet" href="{{ $fontUrl }}" media="print" onload="this.media='all'">
	<noscript>
		<link rel="stylesheet" href="{{ $fontUrl }}">
	</noscript>

	<link rel="preload" as="style" href="{{ $frontendCss('bootstrap.min.css') }}">
	<link rel="preload" as="style" href="{{ $themeCss }}">
	@if($logoWebpUrl)
	<link rel="preload" as="image" href="{{ $logoPreloadUrl }}" type="image/webp">
	@else
	<link rel="preload" as="image" href="{{ $logoPreloadUrl }}">
	@endif
	@if($lcpImage)
	<link rel="preload" as="image" href="{{ $lcpImage }}" fetchpriority="high">
	@endif
	@stack('head')

	{{-- Critical CSS only (blocks first paint). Everything else is deferred. --}}
	<link rel="stylesheet" href="{{ $frontendCss('bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ $themeCss }}">
	{!! $deferCss($frontendCss('randevu-overrides.min.css')) !!}
	{!! $deferCss($frontendCss('fontawesome.min.css')) !!}
	<link rel="preload" as="font" type="font/woff2" crossorigin
		href="{{ asset('frontend/assets/fonts/fontawesome/fa-solid-900.woff2') }}">
	{!! $deferCss($frontendCss('swiper-bundle.min.css')) !!}
	{!! $deferCss($frontendCss('magnific-popup.min.css')) !!}
	@stack('styles')
	<style>
		.skip-link {
			position: absolute;
			left: -9999px;
			top: 0;
			z-index: 10000;
			background: #0b1b4a;
			color: #fff;
			padding: 8px 16px;
		}
		.skip-link:focus {
			left: 8px;
			top: 8px;
		}
	</style>
</head>

<body>
	@include('frontend.layout.partials.google-tag-manager-noscript')
	<a class="skip-link" href="#main-content">{{ __('main.skip_to_content') }}</a>

	<div class="th-menu-wrapper">
		<div class="th-menu-area text-center">
			<button class="th-menu-toggle" type="button" aria-label="{{ __('main.wa_close') }}"><i class="fal fa-times"></i></button>
			<div class="mobile-logo">
				<a href="{{ frontend_route('frontend.home') }}">
					@include('frontend.layout.partials.site-logo', ['logoFetchPriority' => 'high'])
				</a>
			</div>
			<div class="th-mobile-menu">
				<ul>
					<li><a href="{{ frontend_route('frontend.home') }}">{{ __('main.home') }}</a></li>
					<li><a href="{{ frontend_route('frontend.about') }}">{{ __('main.about') }}</a></li>
					<li><a href="{{ frontend_route('frontend.services') }}">{{ __('main.services') }}</a></li>
					<li><a href="{{ frontend_route('frontend.clinics') }}">{{ __('main.clinics') }}</a></li>
					<li><a href="{{ frontend_route('frontend.doctors') }}">{{ __('doctors.page_title') }}</a></li>
					<li><a href="{{ frontend_route('frontend.blog') }}">{{ __('main.blogs') }}</a></li>
					<li><a href="{{ frontend_route('frontend.faq') }}">{{ __('main.faq') }}</a></li>
					<li><a href="{{ frontend_route('frontend.subscription') }}">{{ __('main.subscription') }}</a></li>
					<li><a href="{{ frontend_route('frontend.contact') }}">{{ __('main.contact') }}</a></li>
					<li><a href="{{ frontend_route('frontend.social') }}">{{ __('main.social_media') }}</a></li>
					@include('frontend.layout.partials.multi-language-menu')
				</ul>
			</div>
		</div>
	</div>

	@if(Route::is('frontend.home') || Route::is('frontend.ar.home'))
		@include('frontend.layout.header_1')
	@else
		@include('frontend.layout.header_2')
	@endif

	@include('frontend.layout.partials.book-demo-modal')

	<main id="main-content">
		@yield('content')
	</main>

	@include('frontend.layout.footer')
	@include('frontend.layout.partials.whatsapp-support')

	<div class="scroll-top">
		<svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102" aria-hidden="true">
			<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
				style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
			</path>
		</svg>
	</div>

	{{-- Critical path: menu, slider, UI --}}
	<script src="{{ $frontendJs('vendor/jquery-3.7.1.min.js') }}" defer></script>
	<script src="{{ $frontendJs('swiper-bundle.min.js') }}" defer></script>
	<script src="{{ $frontendJs('bootstrap.min.js') }}" defer></script>
	<script src="{{ $frontendJs('main.min.js') }}" defer></script>
	{{-- Below-the-fold / enhancement libs: load after first paint so they don't block mobile PSI --}}
	<script>
	(function () {
		var secondary = [
			@json($frontendJs('jquery.magnific-popup.min.js')),
			@json($frontendJs('jquery.counterup.min.js')),
			@json($frontendJs('circle-progress.js')),
			@json($frontendJs('nice-select.min.js')),
			@json($frontendJs('wow.min.js')),
			@json($frontendJs('gsap.min.js')),
			@json($frontendJs('ScrollTrigger.min.js')),
			@json($frontendJs('SplitText.js'))
		];
		function loadOne(src) {
			return new Promise(function (resolve) {
				var s = document.createElement('script');
				s.src = src;
				s.async = false;
				s.onload = s.onerror = function () { resolve(); };
				document.body.appendChild(s);
			});
		}
		function loadSecondary() {
			secondary.reduce(function (chain, src) {
				return chain.then(function () { return loadOne(src); });
			}, Promise.resolve()).then(function () {
				document.dispatchEvent(new CustomEvent('rundevo:secondary-scripts'));
				if (typeof window.rundevoInitEnhancements === 'function') {
					window.rundevoInitEnhancements();
				}
			});
		}
		function schedule() {
			if ('requestIdleCallback' in window) {
				requestIdleCallback(loadSecondary, { timeout: 2500 });
			} else {
				setTimeout(loadSecondary, 1);
			}
		}
		if (document.readyState === 'complete') {
			schedule();
		} else {
			window.addEventListener('load', schedule, { once: true });
		}
	})();
	</script>
	@stack('scripts')
</body>

</html>
