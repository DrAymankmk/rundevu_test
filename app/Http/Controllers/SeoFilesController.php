<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class SeoFilesController extends Controller
{
    private const EXCLUDED_URIS = [
        'clear',
        'pusher',
        'test',
        'login',
        'logout',
        'language/{lang}',
        'sitemap.xml',
        'robots.txt',
    ];

    private const EXCLUDED_PREFIXES = [
        'admin',
        'api',
        '_debugbar',
        '_ignition',
        'broadcasting',
        'sanctum',
    ];

    private const CMS_SLUGS_BY_URI = [
        '/' => 'home',
        'about' => 'about',
        'services' => 'services',
        'faq' => 'faq',
        'subscription' => 'subscription',
        'contact' => 'contact',
    ];

    public function sitemap(Request $request)
    {
        $baseUrl = $this->baseUrl($request);
        $cmsPages = $this->cmsPagesBySlug();
        $defaultLastModified = date('Y-m-d', filemtime(base_path('routes/web.php')));

        $urls = $this->publicRoutes()
            ->map(function ($route) use ($baseUrl, $cmsPages, $defaultLastModified) {
                $uri = $route->uri() === '/' ? '/' : trim($route->uri(), '/');
                $cmsSlug = self::CMS_SLUGS_BY_URI[$uri] ?? null;
                $lastModified = $cmsSlug && isset($cmsPages[$cmsSlug])
                    ? optional($cmsPages[$cmsSlug]->updated_at)->format('Y-m-d')
                    : $defaultLastModified;

                return [
                    'loc' => $this->absoluteUrl($baseUrl, $uri),
                    'lastmod' => $lastModified ?: $defaultLastModified,
                    'changefreq' => $uri === '/' ? 'weekly' : 'monthly',
                    'priority' => $uri === '/' ? '1.0' : '0.8',
                ];
            })
            ->unique('loc')
            ->values();

        $xml = $this->buildSitemapXml($urls);

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(Request $request)
    {
        $baseUrl = $this->baseUrl($request);

        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Disallow: /api/',
            'Disallow: /clear',
            'Disallow: /pusher',
            'Disallow: /test',
            '',
            'Sitemap: ' . $baseUrl . '/sitemap.xml',
            '',
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    private function publicRoutes(): Collection
    {
        return collect(Route::getRoutes())->filter(function ($route) {
            $uri = $route->uri() === '/' ? '/' : trim($route->uri(), '/');
            $middleware = $route->gatherMiddleware();
            $name = (string) $route->getName();

            if (!in_array('GET', $route->methods(), true)) {
                return false;
            }

            if ($route->parameterNames()) {
                return false;
            }

            if (in_array($uri, self::EXCLUDED_URIS, true)) {
                return false;
            }

            if (Str::startsWith($name, ['admin.', 'cms.'])) {
                return false;
            }

            if (in_array('auth', $middleware, true)) {
                return false;
            }

            foreach (self::EXCLUDED_PREFIXES as $prefix) {
                if ($uri === $prefix || Str::startsWith($uri, $prefix . '/')) {
                    return false;
                }
            }

            return true;
        });
    }

    private function cmsPagesBySlug(): Collection
    {
        try {
            if (!Schema::hasTable('cms_pages')) {
                return collect();
            }

            return CmsPage::query()
                ->whereIn('slug', array_filter(self::CMS_SLUGS_BY_URI))
                ->get(['slug', 'updated_at'])
                ->keyBy('slug');
        } catch (Throwable $exception) {
            return collect();
        }
    }

    private function baseUrl(Request $request): string
    {
        $configuredUrl = trim((string) config('app.url'), '/');

        if ($configuredUrl && $configuredUrl !== 'http://localhost') {
            return $configuredUrl;
        }

        return rtrim($request->getSchemeAndHttpHost(), '/');
    }

    private function absoluteUrl(string $baseUrl, string $uri): string
    {
        if ($uri === '/') {
            return $baseUrl . '/';
        }

        return $baseUrl . '/' . ltrim($uri, '/');
    }

    private function buildSitemapXml(Collection $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>' . e($url['loc']) . "</loc>\n";
            $xml .= '        <lastmod>' . e($url['lastmod']) . "</lastmod>\n";
            $xml .= '        <changefreq>' . e($url['changefreq']) . "</changefreq>\n";
            $xml .= '        <priority>' . e($url['priority']) . "</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= "</urlset>\n";

        return $xml;
    }
}
