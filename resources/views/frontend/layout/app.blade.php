<!doctype html>
@php
    // Locale is resolved from the URL by SetLocale middleware (not session).
    // Avoid preferring session('lang') — that key is shared with the admin dashboard.
    $effectiveLocale = strtolower(trim(str_replace('_', '-', (string) app()->getLocale())));
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
    $logoWebpUrl = frontend_logo_webp_available() ? frontend_logo_url('webp') : null;
    $logoPreloadUrl = $logoWebpUrl ?: $logoPngUrl;
    // Render-blocking CSS: use the copies with unused template rules removed
    // (built by tools/purge_theme_css.py), or the full files if they are missing.
    $purgedCss = static function (string $file) use ($frontendCss): string {
        $purged = str_replace('.min.css', '.purged.min.css', $file);

        return $frontendCss(is_file(public_path('frontend/assets/css/' . $purged)) ? $purged : $file);
    };
    $bootstrapCss = $purgedCss('bootstrap.min.css');
    $themeCss = $purgedCss($isRtl ? 'rtl_style.min.css' : 'style.min.css');
    // Upright faces only: the theme CSS never requests italics, so those font files were dead weight.
    // No Inter "opsz" axis: it made the Inter file roughly 50% larger for a barely visible difference.
    $fontUrl = 'https://fonts.googleapis.com/css2?family=Inter:wght@400..700&family=Outfit:wght@400..700&family=Saira:wght@400..700&display=swap';
    $lcpImage = $seo['lcp_image'] ?? null;
    $deferCss = static function (string $href): string {
        return '<link rel="stylesheet" href="' . e($href) . '" media="print" onload="this.media=\'all\'">'
            . '<noscript><link rel="stylesheet" href="' . e($href) . '"></noscript>';
    };
@endphp
<html class="no-js" lang="{{ $htmlLang }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    @php
        $hasSession = false;
        try {
            $hasSession = request()->hasSession() && request()->session()->isStarted();
        } catch (\Throwable $e) {
            $hasSession = false;
        }
    @endphp
    @if($hasSession)
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
    <meta name="csrf-token-url" content="{{ url('/csrf-token') }}">
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

    <link rel="preload" as="style" href="{{ $bootstrapCss }}">
    <link rel="preload" as="style" href="{{ $themeCss }}">
    @if($logoWebpUrl)
        <link rel="preload" as="image" href="{{ $logoPreloadUrl }}" type="image/webp">
    @else
        <link rel="preload" as="image" href="{{ $logoPreloadUrl }}">
    @endif
    @if($lcpImage)
        <link rel="preload" as="image" href="{{ $lcpImage }}" @if(!empty($seo['lcp_image_srcset'])) imagesrcset="{{ $seo['lcp_image_srcset'] }}" imagesizes="100vw" @endif fetchpriority="high">
    @endif
    @stack('head')

    {{-- Critical CSS only (blocks first paint). Everything else is deferred. --}}
    <link rel="stylesheet" href="{{ $bootstrapCss }}">
    <link rel="stylesheet" href="{{ $themeCss }}">
    {!! $deferCss($frontendCss('randevu-overrides.min.css')) !!}
    {{-- Icons the site uses (~20 KB CSS + 12 KB fonts, built by tools/build_fontawesome_subset.py).
         The other icon rules load only if the page shows an icon outside the subset (see bottom). --}}
    <link rel="stylesheet" href="{{ $frontendCss('fontawesome-subset.min.css') }}" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="{{ $frontendCss('fontawesome-subset.min.css') }}">
        <link rel="stylesheet" href="{{ $frontendCss('fontawesome-rest.min.css') }}">
    </noscript>
    {!! $deferCss($frontendCss('swiper-bundle.min.css')) !!}
    {!! $deferCss($frontendCss('magnific-popup.min.css')) !!}
    @stack('styles')
    <style>
        /* Prevent RTL pages from gaining a huge horizontal scroll area.
           body already uses overflow-x:hidden; html must too or off-screen
           absolute children (e.g. skip-link) expand document scrollWidth. */
        html {
            overflow-x: clip;
        }
        .skip-link {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            clip-path: inset(50%);
            white-space: nowrap;
            border: 0;
            z-index: 10000;
            background: #0b1b4a;
            color: #fff;
        }
        .skip-link:focus {
            position: fixed;
            inset-inline-start: 8px;
            top: 8px;
            width: auto;
            height: auto;
            margin: 0;
            padding: 8px 16px;
            overflow: visible;
            clip: auto;
            clip-path: none;
            white-space: normal;
        }
    </style>
</head>

<body>
@include('frontend.layout.partials.google-tag-manager-noscript')
<a class="skip-link" href="#main-content">{{ __('main.skip_to_content') }}</a>

<div class="th-menu-wrapper">
    <div class="th-menu-area text-center">
        <button class="th-menu-toggle" type="button" aria-label="{{ __('main.menu_close') }}"><i class="fal fa-times" aria-hidden="true"></i></button>
        <div class="mobile-logo">
            <a href="{{ frontend_route('frontend.home') }}">
                @include('frontend.layout.partials.site-logo', ['logoFetchPriority' => 'high'])
            </a>
        </div>
        <div class="th-mobile-menu" id="mobile-menu">
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
              style="stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
        </path>
    </svg>
</div>

{{-- Below-the-fold section backgrounds (data-lazy-bg): fetch only when near the screen,
     so they do not compete with the hero for bandwidth on phones. --}}
<script>
    (function () {
        var els = document.querySelectorAll('[data-lazy-bg]');
        function show(el) {
            el.style.backgroundImage = 'url("' + el.getAttribute('data-lazy-bg') + '")';
            el.removeAttribute('data-lazy-bg');
        }
        if (!('IntersectionObserver' in window)) {
            els.forEach(show);
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    io.unobserve(entry.target);
                    show(entry.target);
                }
            });
        }, { rootMargin: '400px 0px' });
        els.forEach(function (el) { io.observe(el); });
    })();
</script>

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
            // WOW removed: overlaps GSAP ScrollTrigger for scroll reveals
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
{{-- Load the remaining Font Awesome icon rules only when an icon on the page has no glyph yet
     (e.g. an icon picked in the CMS that is not in the prebuilt subset). --}}
<script>
    (function () {
        var restHref = @json($frontendCss('fontawesome-rest.min.css'));
        var added = false;
        function missingIcon() {
            var els = document.querySelectorAll('[class*="fa-"]');
            for (var i = 0; i < els.length; i++) {
                var cls = typeof els[i].className === 'string' ? els[i].className : (els[i].getAttribute('class') || '');
                if (!/(^|\s)fa-[a-z0-9]/.test(cls)) continue;
                var before = getComputedStyle(els[i], '::before');
                if (before.fontFamily.indexOf('Font Awesome') === -1) continue;
                if (!before.content || before.content === 'none' || before.content === 'normal') return true;
            }
            return false;
        }
        function check() {
            if (added || !missingIcon()) return;
            added = true;
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = restHref;
            document.head.appendChild(link);
        }
        function schedule() {
            if ('requestIdleCallback' in window) {
                requestIdleCallback(check, { timeout: 2000 });
            } else {
                setTimeout(check, 200);
            }
        }
        if (document.readyState === 'complete') {
            schedule();
        } else {
            window.addEventListener('load', schedule, { once: true });
        }
        document.addEventListener('rundevo:secondary-scripts', schedule);
        window.rundevoCheckIcons = check;
    })();
</script>
@if(config('website_visit_logs.enabled', true) && !empty($websiteVisitLogDeferred))
    <script>
        window.__VISIT_LOG__ = {
            id: null,
            token: null,
            deferred: true,
            heartbeat: {{ (int) config('website_visit_logs.heartbeat_seconds', 15) }},
            endpoint: @json(route('website-visit-logs.beacon')),
            bootstrap: @json($websiteVisitBootstrapEndpoint ?? route('website-visit-logs.bootstrap')),
            csrf: null
        };
    </script>
    <script src="{{ $frontendJs('visit-tracker.js') }}" defer></script>
@endif
@stack('scripts')
</body>

</html>
