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
$frontendCss = static fn (string $file) => asset('frontend/assets/css/' . ltrim($file, '/'));
$frontendJs = static fn (string $file) => asset('frontend/assets/js/' . ltrim($file, '/'));
$logoUrl = asset('frontend/assets/img/logo.png');
$themeCss = $isRtl ? $frontendCss('rtl_style.css') : $frontendCss('style.css');
$fontUrl = 'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&family=Outfit:wght@400;500;600;700&family=Saira:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap';
$lcpImage = $seo['lcp_image'] ?? null;
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

	<link rel="icon" type="image/png" href="{{ $logoUrl }}">
	<link rel="apple-touch-icon" href="{{ $logoUrl }}">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preload" as="style" href="{{ $fontUrl }}">
	<link rel="stylesheet" href="{{ $fontUrl }}" media="print" onload="this.media='all'">
	<noscript>
		<link rel="stylesheet" href="{{ $fontUrl }}">
	</noscript>

	<link rel="preload" as="style" href="{{ $frontendCss('bootstrap.min.css') }}">
	<link rel="preload" as="style" href="{{ $themeCss }}">
	<link rel="preload" as="image" href="{{ $logoUrl }}">
	@if($lcpImage)
	<link rel="preload" as="image" href="{{ $lcpImage }}" fetchpriority="high">
	@endif
	@stack('head')

	<link rel="stylesheet" href="{{ $frontendCss('bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ $frontendCss('fontawesome.min.css') }}">
	{{-- Only solid + brands ship now; preload solid for above-the-fold icons --}}
	<link rel="preload" as="font" type="font/woff2" crossorigin
		href="{{ asset('frontend/assets/fonts/fontawesome/fa-solid-900.woff2') }}">
	<link rel="stylesheet" href="{{ $frontendCss('swiper-bundle.min.css') }}">
	<link rel="stylesheet" href="{{ $themeCss }}">
	<link rel="stylesheet" href="{{ $frontendCss('randevu-overrides.css') }}">
	<link rel="stylesheet" href="{{ $frontendCss('magnific-popup.min.css') }}" media="print" onload="this.media='all'">
	<noscript>
		<link rel="stylesheet" href="{{ $frontendCss('magnific-popup.min.css') }}">
	</noscript>
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
					<img src="{{ $logoUrl }}" width="100" height="50" alt="{{ config('app.name', 'Randevu') }}" decoding="async" fetchpriority="high">
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
	<script src="{{ $frontendJs('main.js') }}" defer></script>
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
