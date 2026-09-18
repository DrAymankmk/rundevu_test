<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Http;
use Throwable;

class FrontendPerformanceAuditor
{
    private const HEAVY_JS = [
        'threesixty.min.js',
        '360.min.js',
        'panolens.min.js',
        'jquery-ui.min.js',
        'isotope.pkgd.min.js',
    ];

    private const LAYOUT = 'resources/views/frontend/layout/app.blade.php';
    private const SEO_PARTIAL = 'resources/views/frontend/layout/partials/seo-meta.blade.php';

    /**
     * @return array{summary: array<string, int>, checks: array<int, array<string, mixed>>}
     */
    public function audit(?string $url = null): array
    {
        $checks = array_merge(
            $this->auditLayout(),
            $this->auditSeoPartial(),
            $this->auditRoutes(),
            $this->auditControllers(),
            $this->auditAssets(),
            $this->auditFrontendViews()
        );

        if ($url) {
            $checks = array_merge($checks, $this->auditLivePage($url));
        }

        $summary = ['pass' => 0, 'fail' => 0, 'warn' => 0];
        foreach ($checks as $check) {
            $summary[$check['status']] = ($summary[$check['status']] ?? 0) + 1;
        }

        return [
            'summary' => $summary,
            'checks' => $checks,
        ];
    }

    public function toMarkdown(array $result): string
    {
        $s = $result['summary'];
        $lines = [
            '# Frontend performance check',
            '',
            'Generated: ' . now()->toDateTimeString(),
            '',
            sprintf('- Pass: %d', $s['pass'] ?? 0),
            sprintf('- Fail: %d', $s['fail'] ?? 0),
            sprintf('- Warn: %d', $s['warn'] ?? 0),
            '',
            '| Status | Category | Check | How to handle |',
            '|---|---|---|---|',
        ];

        foreach ($result['checks'] as $check) {
            $lines[] = sprintf(
                '| %s | %s | %s | %s |',
                strtoupper($check['status']),
                $check['category'],
                str_replace('|', '/', $check['title'] . ' — ' . $check['detail']),
                str_replace('|', '/', $check['fix'])
            );
        }

        $lines[] = '';
        $lines[] = 'See `docs/FRONTEND_PERFORMANCE.md` for the codebase playbook.';

        return implode("\n", $lines) . "\n";
    }

    private function auditLayout(): array
    {
        $path = base_path(self::LAYOUT);
        $html = is_file($path) ? file_get_contents($path) : '';

        return [
            $this->check(
                'layout-exists',
                'performance',
                $html !== '',
                'Frontend layout file exists',
                self::LAYOUT,
                'Create or restore resources/views/frontend/layout/app.blade.php'
            ),
            $this->check(
                'no-leading-asset-space',
                'performance',
                !preg_match('/(?:src|href)=["\']\s+\{\{\s*asset\(/', $html),
                'Asset URLs have no leading whitespace',
                'Found `href=" {{ asset(...) }}` which breaks caching and can 404.',
                'Use href="{{ asset(\'...\') }}" with no space after the quote. File: ' . self::LAYOUT
            ),
            $this->check(
                'scripts-deferred',
                'performance',
                $this->allSrcScriptsAreDeferred($html),
                'Layout scripts use defer',
                'Count of src scripts vs deferred scripts.',
                'Add defer to every frontend script in app.blade.php so HTML parsing is not blocked. Keep order: jquery → plugins → main.js'
            ),
            $this->check(
                'heavy-js-not-global',
                'performance',
                !$this->layoutLoadsHeavyJs($html),
                '360/panolens/isotope/jquery-ui are not loaded on every page',
                'These files are 20KB–556KB and unused on marketing pages.',
                'Load them only from @push(\'scripts\') on pages that need panorama/filters. Guard main.js with typeof PANOLENS / $.fn.isotope'
            ),
            $this->check(
                'fonts-nonblocking',
                'performance',
                str_contains($html, "media=\"print\"") && str_contains($html, 'fonts.googleapis.com'),
                'Google Fonts load without blocking first paint',
                'Use preload + media="print" onload="this.media=\'all\'" + noscript fallback.',
                'Keep display=swap on the font URL. Do not add more families (Inter/Outfit/Saira is already three).'
            ),
            $this->check(
                'lcp-preload-stack',
                'performance',
                str_contains($html, "rel=\"preload\"") && str_contains($html, '$lcpImage'),
                'Layout can preload the LCP image',
                'HomeController / listing controllers should set $seo[\'lcp_image\'].',
                'Set $seo[\'lcp_image\'] in the page controller. Layout already preloads it in <head>.'
            ),
            $this->check(
                'no-theme-html-links',
                'seo',
                !preg_match('/href=["\'][^"\']+\.html["\']/', $html),
                'Layout has no dummy theme HTML links',
                'service.html / blog.html links waste crawl budget and look broken.',
                'Replace with frontend_route(\'frontend.*\') in app.blade.php, headers, and fallback sections.'
            ),
        ];
    }

    private function auditSeoPartial(): array
    {
        $path = base_path(self::SEO_PARTIAL);
        $html = is_file($path) ? file_get_contents($path) : '';

        $required = [
            'canonical' => 'rel="canonical"',
            'robots' => 'name="robots"',
            'og:title' => 'property="og:title"',
            'og:image' => 'property="og:image"',
            'twitter:card' => 'name="twitter:card"',
            'hreflang' => 'hreflang',
            'json-ld' => 'application/ld+json',
        ];

        $checks = [];
        foreach ($required as $id => $needle) {
            $checks[] = $this->check(
                'seo-' . $id,
                'seo',
                str_contains($html, $needle),
                'SEO partial includes ' . $id,
                self::SEO_PARTIAL,
                'Add the tag in seo-meta.blade.php and populate it from App\\Services\\Seo\\SeoResolver'
            );
        }

        return $checks;
    }

    private function auditRoutes(): array
    {
        $web = is_file(base_path('routes/web.php')) ? file_get_contents(base_path('routes/web.php')) : '';

        return [
            $this->check(
                'robots-route',
                'seo',
                str_contains($web, 'robots.txt') || str_contains($web, 'SeoFilesController@robots'),
                'robots.txt is served by Laravel',
                'public/.htaccess rewrites robots.txt to index.php, so a route is required.',
                'Keep Route::get(\'/robots.txt\', \'SeoFilesController@robots\') in routes/web.php'
            ),
            $this->check(
                'sitemap-route',
                'seo',
                str_contains($web, 'sitemap.xml') || str_contains($web, 'SitemapController'),
                'sitemap.xml route exists',
                'routes/web.php',
                'Keep Route::get(\'/sitemap.xml\', \'SitemapController\') and include blogs/clinics/doctors + hreflang'
            ),
        ];
    }

    private function auditAssets(): array
    {
        $checks = [];
        $limits = [
            'public/frontend/assets/img/icon/apple.svg' => 30,
            'public/frontend/assets/img/icon/google-play.svg' => 20,
            'public/frontend/assets/img/bg/breadcumb-bg.jpg' => 250,
            'public/frontend/assets/img/bg/breadcumb-doctors.jpg' => 250,
            'public/frontend/assets/img/bg/breadcumb-clinics.jpg' => 250,
            'public/frontend/assets/img/bg/breadcumb-clinic-details.jpg' => 250,
            'public/frontend/assets/img/bg/breadcumb-doctor-details.jpg' => 250,
            'public/frontend/assets/img/hero/hero_bg_1_1.jpg' => 250,
        ];

        foreach ($limits as $relative => $maxKb) {
            $full = base_path($relative);
            $kb = is_file($full) ? round(filesize($full) / 1024, 1) : 0;
            $checks[] = $this->check(
                'asset-' . basename($relative),
                'assets',
                is_file($full) && $kb <= $maxKb,
                basename($relative) . ' is within ' . $maxKb . ' KB',
                is_file($full) ? $kb . ' KB' : 'missing',
                'Compress with php artisan frontend:perf-check --compress or export WebP/JPEG at 1600px, quality 72. Store badges must be real SVGs, not traced PNGs.'
            );
        }

        return $checks;
    }

    private function auditFrontendViews(): array
    {
        $views = $this->frontendViewContents();
        $joined = implode("\n", $views);
        $joined = preg_replace('/\{\{--.*?--\}\}/s', '', $joined) ?? $joined;
        $joined = preg_replace('/<!--.*?-->/s', '', $joined) ?? $joined;
        $leading = preg_match('/(?:src|href|data-bg-src)=["\']\s+\{\{\s*asset\(/', $joined);
        $dummy = preg_match('/href=["\'][^"\']+\.html["\']/', $joined);
        $dataBg = preg_match('/\sdata-bg-src=/', $joined);
        $themeAssets = preg_match('/(?:src|href)=["\']assets\//', $joined);
        $missingImgHint = $this->firstImgMissingLazyHint($views);
        $breadcrumbInline = str_contains($joined, 'breadcumb-wrapper')
            && str_contains($joined, "background-image: url(");

        return [
            $this->check(
                'views-no-leading-space',
                'performance',
                !$leading,
                'Frontend views have no spaced asset URLs',
                $leading ? 'At least one blade still has " {{ asset(" with a leading space.' : 'Clean',
                'Search resources/views/frontend for `" {{ asset` and remove the space.'
            ),
            $this->check(
                'views-no-dummy-html',
                'seo',
                !$dummy,
                'Frontend views have no *.html theme links',
                $dummy ? 'Replace leftover contact.html / service.html links with frontend_route().' : 'Clean',
                'Especially fallback CMS sections in resources/views/frontend/pages/home/sections/'
            ),
            $this->check(
                'views-no-data-bg-src',
                'performance',
                !$dataBg,
                'Frontend views do not use data-bg-src for LCP backgrounds',
                $dataBg ? 'data-bg-src waits for jQuery in main.js and delays LCP.' : 'Clean',
                'Use inline style="background-image: url(...)" or frontend_bg_style() / x-breadcrumb.'
            ),
            $this->check(
                'views-no-theme-asset-root',
                'performance',
                !$themeAssets,
                'Frontend views do not point at theme assets/img paths',
                $themeAssets ? 'Found src="assets/..." which 404s on Laravel (files live under public/frontend/assets).' : 'Clean',
                'Use asset(\'frontend/assets/img/...\') or frontend_media_url().'
            ),
            $this->check(
                'views-imgs-lazy-or-priority',
                'performance',
                $missingImgHint === null,
                'Images use loading=lazy or fetchpriority',
                $missingImgHint ?? 'Clean',
                'Below-fold: loading="lazy" decoding="async". Header logos / LCP photos: fetchpriority="high", never lazy.'
            ),
            $this->check(
                'views-breadcrumb-inline-bg',
                'performance',
                $breadcrumbInline,
                'Breadcrumb uses an inline background-image',
                $breadcrumbInline ? 'x-breadcrumb / breadcumb-wrapper inlines the JPEG.' : 'Missing inline breadcrumb background.',
                'Use <x-breadcrumb> or style="background-image: url(\'{{ frontend_breadcrumb_image() }}\');"'
            ),
        ];
    }

    private function auditControllers(): array
    {
        $dir = app_path('Http/Controllers/Frontend');
        $missing = [];
        foreach (glob($dir . '/*.php') ?: [] as $file) {
            $src = (string) file_get_contents($file);
            if (!preg_match('/function (index|show)\(/', $src)) {
                continue;
            }
            if (
                !str_contains($src, "'lcp_image'")
                && !str_contains($src, 'resolveForCmsSlug')
            ) {
                $missing[] = basename($file);
            }
        }

        return [
            $this->check(
                'controllers-lcp-image',
                'performance',
                $missing === [],
                'Public frontend controllers set LCP images',
                $missing === [] ? 'All index/show actions set lcp_image or use resolveForCmsSlug().' : implode(', ', $missing),
                'Pass lcp_image into SeoResolver::resolve() / defaults(), or use resolveForCmsSlug() which already sets a breadcrumb/hero default.'
            ),
        ];
    }

    private function auditLivePage(string $url): array
    {
        try {
            $response = Http::timeout(20)->withHeaders([
                'User-Agent' => 'RandevuFrontendPerfCheck/1.0',
            ])->get($url);

            $html = (string) $response->body();
            $ok = $response->successful();
        } catch (Throwable $e) {
            return [
                $this->check('live-fetch', 'performance', false, 'Could not fetch live URL', $e->getMessage(), 'Pass --url=http://127.0.0.1:8002 with php artisan serve running'),
            ];
        }

        $hasTitle = (bool) preg_match('/<title>[^<]+<\/title>/i', $html);
        $hasCanonical = str_contains($html, 'rel="canonical"');
        $hasHreflang = str_contains($html, 'hreflang=');
        $hasJsonLd = str_contains($html, 'application/ld+json');
        $blockingScripts = preg_match_all('/<script[^>]+src=/i', $html);
        $deferredScripts = preg_match_all('/<script[^>]+src=[^>]*\sdefer/i', $html);
        $heavyPresent = false;
        foreach (self::HEAVY_JS as $file) {
            if (str_contains($html, $file)) {
                $heavyPresent = true;
                break;
            }
        }

        return [
            $this->check('live-status', 'performance', $ok, 'Live page returns HTTP 2xx', 'Status ' . $response->status(), 'Fix routing / APP_URL / php artisan serve'),
            $this->check('live-title', 'seo', $hasTitle, 'Live page has a non-empty title', 'Inspect SeoResolver defaults and CMS SEO meta', 'Fill meta_title in CMS or pass title into SeoResolver::resolve()'),
            $this->check('live-canonical', 'seo', $hasCanonical, 'Live page has canonical', '', 'seo-meta.blade.php + SeoResolver canonical'),
            $this->check('live-hreflang', 'seo', $hasHreflang, 'Live page has hreflang alternates', 'Required for / and /ar URLs', 'frontend_hreflang_urls() is applied in SeoResolver'),
            $this->check('live-jsonld', 'seo', $hasJsonLd, 'Live page has JSON-LD', '', 'SeoResolver default Organization/WebPage graph, or CMS schema_json'),
            $this->check(
                'live-defer',
                'performance',
                $this->liveScriptsAreNonBlocking($html),
                'Live page scripts are deferred',
                sprintf('%d src scripts, %d deferred', $blockingScripts, $deferredScripts),
                'Defer scripts in app.blade.php. Inline @stack(\'scripts\') is allowed.'
            ),
            $this->check(
                'live-no-heavy-js',
                'performance',
                !$heavyPresent,
                'Live HTML does not include 360/panolens globally',
                $heavyPresent ? 'Heavy plugin still referenced.' : 'Clean',
                'Remove those script tags from app.blade.php'
            ),
        ];
    }

    private function liveScriptsAreNonBlocking(string $html): bool
    {
        preg_match_all('/<script\b([^>]*)>/i', $html, $matches);
        foreach ($matches[1] as $attrs) {
            if (!preg_match('/\bsrc=/i', $attrs)) {
                continue;
            }
            if (!preg_match('/\bdefer\b/i', $attrs) && !preg_match('/\basync\b/i', $attrs)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<int, string>
     */
    private function frontendViewContents(): array
    {
        $contents = [];
        $roots = [
            resource_path('views/frontend'),
            resource_path('views/components/breadcrumb.blade.php'),
        ];

        foreach ($roots as $root) {
            if (is_file($root) && str_ends_with($root, '.blade.php')) {
                $contents[] = (string) file_get_contents($root);
                continue;
            }
            if (!is_dir($root)) {
                continue;
            }
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));
            foreach ($files as $file) {
                if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                    $contents[] = (string) file_get_contents($file->getPathname());
                }
            }
        }

        return $contents;
    }

    /**
     * @param  array<int, string>  $views
     */
    private function firstImgMissingLazyHint(array $views): ?string
    {
        foreach ($views as $html) {
            $html = preg_replace('/\{\{--.*?--\}\}/s', '', $html) ?? $html;
            $html = preg_replace('/<!--.*?-->/s', '', $html) ?? $html;
            foreach ($this->imgTags($html) as $tag) {
                if (preg_match('/\bloading=/i', $tag) || preg_match('/\bfetchpriority=/i', $tag)) {
                    continue;
                }

                return \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', $tag) ?? $tag), 120);
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    private function imgTags(string $html): array
    {
        $tags = [];
        $offset = 0;
        $length = strlen($html);

        while (($start = stripos($html, '<img', $offset)) !== false) {
            $i = $start + 4;
            $depth = 0;
            $end = null;
            while ($i < $length) {
                $char = $html[$i];
                $next = $i + 1 < $length ? $html[$i + 1] : '';
                if ($char === '{' && $next === '{') {
                    $depth++;
                    $i += 2;
                    continue;
                }
                if ($char === '}' && $next === '}' && $depth > 0) {
                    $depth--;
                    $i += 2;
                    continue;
                }
                if ($char === '>' && $depth === 0) {
                    $end = $i;
                    break;
                }
                $i++;
            }
            if ($end === null) {
                break;
            }
            $tags[] = substr($html, $start, $end - $start + 1);
            $offset = $end + 1;
        }

        return $tags;
    }

    private function allSrcScriptsAreDeferred(string $html): bool
    {
        $src = preg_match_all('/<script[^>]+src=/i', $html);
        if ($src === 0) {
            return true;
        }

        $deferred = preg_match_all('/<script[^>]+src=[^>]*\sdefer/i', $html);

        return $deferred === $src;
    }

    private function layoutLoadsHeavyJs(string $html): bool
    {
        foreach (self::HEAVY_JS as $file) {
            if (str_contains($html, $file)) {
                return true;
            }
        }

        return false;
    }

    private function check(string $id, string $category, bool $pass, string $title, string $detail, string $fix, bool $warnOnFail = false): array
    {
        return [
            'id' => $id,
            'category' => $category,
            'status' => $pass ? 'pass' : ($warnOnFail ? 'warn' : 'fail'),
            'title' => $title,
            'detail' => $detail,
            'fix' => $fix,
        ];
    }
}
