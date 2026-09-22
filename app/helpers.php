<?php

use App\Services\ClinicModuleService;
use Illuminate\Support\Facades\Route;

if (! function_exists('admin_language_switch_url')) {
    function admin_language_switch_url(string $lang): string
    {
        $current = request()->getRequestUri();
        if (strpos($current, '/changeLanguageAdmin') !== false || strpos($current, '/admin/login') !== false) {
            $current = '/admin/dashboard';
        }

        return url('/changeLanguageAdmin/' . $lang) . '?redirect=' . rawurlencode($current);
    }
}

if (! function_exists('clinic_has_module')) {
    function clinic_has_module(string $module): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return app(ClinicModuleService::class)->hasModule($user, $module);
    }
}

if (! function_exists('frontend_default_locale')) {
    function frontend_default_locale(): string
    {
        return (string) config('app.fallback_locale', config('app.locale', 'en'));
    }
}

if (! function_exists('frontend_prefixed_locales')) {
    /**
     * Locales that use a URL prefix. English stays unprefixed.
     *
     * @return array<int, string>
     */
    function frontend_prefixed_locales(): array
    {
        return ['ar'];
    }
}

if (! function_exists('frontend_strip_locale_prefix')) {
    function frontend_strip_locale_prefix(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        foreach (frontend_prefixed_locales() as $locale) {
            if ($path === '/' . $locale) {
                return '/';
            }
            if (str_starts_with($path, '/' . $locale . '/')) {
                $stripped = substr($path, strlen($locale) + 1);

                return $stripped === '' ? '/' : $stripped;
            }
        }

        return $path === '' ? '/' : $path;
    }
}

if (! function_exists('frontend_localize_path')) {
    function frontend_localize_path(string $path, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $path = frontend_strip_locale_prefix($path);

        if (in_array($locale, frontend_prefixed_locales(), true)) {
            return $path === '/' ? '/' . $locale : '/' . $locale . $path;
        }

        return $path;
    }
}

if (! function_exists('frontend_url')) {
    function frontend_url(string $path = '/', $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $query = '';

        if (is_array($parameters) && $parameters !== []) {
            $query = '?' . http_build_query($parameters);
        } elseif (is_string($parameters) && $parameters !== '') {
            $query = str_starts_with($parameters, '?') ? $parameters : '?' . $parameters;
        }

        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'mailto:') || str_starts_with($path, 'tel:')) {
            return $path . $query;
        }

        return url(frontend_localize_path($path, $locale)) . $query;
    }
}

if (! function_exists('frontend_reserved_path_segments')) {
    /**
     * @return array<int, string>
     */
    function frontend_reserved_path_segments(): array
    {
        return [
            'about',
            'services',
            'faq',
            'subscription',
            'contact',
            'blog',
            'clinics',
            'doctors',
            'social-media',
            'language',
            'register',
            'terms',
            'clear',
            'pusher',
            'test',
        ];
    }
}

if (! function_exists('frontend_blog_path')) {
    function frontend_blog_path(string $slug, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $slug = trim($slug, '/');

        if (in_array($locale, frontend_prefixed_locales(), true)) {
            return '/' . $slug;
        }

        return '/blog/' . $slug;
    }
}

if (! function_exists('frontend_route')) {
    /**
     * Generate a frontend route URL with optional /ar prefix for Arabic.
     *
     * @param  mixed  $parameters
     */
    function frontend_route(string $name, $parameters = [], bool $absolute = true, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($name === 'frontend.language.switch') {
            $lang = is_array($parameters) ? ($parameters['lang'] ?? $parameters[0] ?? frontend_default_locale()) : $parameters;

            return frontend_language_url((string) $lang);
        }

        if ($name === 'frontend.blog.show') {
            $slug = is_array($parameters)
                ? (string) ($parameters['slug'] ?? $parameters[0] ?? '')
                : (string) $parameters;

            return frontend_url(frontend_blog_path($slug, $locale), [], $locale);
        }

        $suffix = str_starts_with($name, 'frontend.') ? substr($name, 9) : $name;
        $routeName = $name;

        if (in_array($locale, frontend_prefixed_locales(), true)) {
            $localized = 'frontend.' . $locale . '.' . $suffix;
            if (Route::has($localized)) {
                $routeName = $localized;
            }
        }

        return route($routeName, $parameters, $absolute);
    }
}

if (! function_exists('frontend_language_url')) {
    function frontend_language_url(string $lang): string
    {
        $path = frontend_strip_locale_prefix(request()->path());
        $query = request()->getQueryString();
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        $resolveBlogPath = static function (string $slug) use ($lang): ?string {
            $post = \App\Models\BlogPost::query()
                ->published()
                ->whereSlug($slug)
                ->with('translations')
                ->first();

            if (! $post) {
                return null;
            }

            return frontend_blog_path($post->getRouteSlug(), $lang);
        };

        if (count($segments) === 2 && $segments[0] === 'blog') {
            $blogPath = $resolveBlogPath($segments[1]);
            if ($blogPath !== null) {
                $path = $blogPath;
            }
        } elseif (count($segments) === 1 && ! in_array($segments[0], frontend_reserved_path_segments(), true)) {
            $blogPath = $resolveBlogPath($segments[0]);
            if ($blogPath !== null) {
                $path = $blogPath;
            }
        }

        $url = frontend_url($path, [], $lang);

        if ($query) {
            $url .= (str_contains($url, '?') ? '&' : '?') . $query;
        }

        return $url;
    }
}

if (! function_exists('frontend_hreflang_urls')) {
    /**
     * Map locale codes (plus x-default) to the current public URL.
     *
     * @param  array<string, string>  $overrides  locale => url
     * @return array<string, string>
     */
    function frontend_hreflang_urls(?string $path = null, array $overrides = []): array
    {
        $path = $path ?? frontend_strip_locale_prefix('/' . ltrim(request()->path(), '/'));
        $locales = config('app.locales');
        if (! is_array($locales) || $locales === []) {
            $locales = ['en', 'ar'];
        }

        $links = [];
        foreach ($locales as $locale) {
            $links[$locale] = $overrides[$locale] ?? frontend_url($path, [], $locale);
        }

        $defaultLocale = frontend_default_locale();
        $links['x-default'] = $overrides[$defaultLocale] ?? $links[$defaultLocale] ?? frontend_url($path, [], $defaultLocale);

        return $links;
    }
}

if (! function_exists('frontend_media_url')) {
    function frontend_media_url(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (str_starts_with($path, 'frontend/')) {
            return asset($path);
        }
        if (str_starts_with($path, 'assets/')) {
            return asset('frontend/' . $path);
        }

        return asset($path);
    }
}

if (! function_exists('frontend_breadcrumb_slot_map')) {
    /**
     * page_key => relative path under public/frontend/assets/img
     *
     * @return array<string, string>
     */
    function frontend_breadcrumb_slot_map(): array
    {
        static $map;

        if ($map !== null) {
            return $map;
        }

        $map = [];
        if (class_exists(\App\Services\Frontend\FrontendMediaCatalog::class)) {
            foreach (\App\Services\Frontend\FrontendMediaCatalog::breadcrumbSlots() as $slot) {
                $pageKey = (string) ($slot['page_key'] ?? $slot['id'] ?? '');
                $path = (string) ($slot['path'] ?? '');
                if ($pageKey === '' || $path === '') {
                    continue;
                }
                $prefix = 'frontend/assets/img/';
                $file = str_starts_with($path, $prefix) ? substr($path, strlen($prefix)) : $path;
                $map[$pageKey] = ltrim($file, '/');
            }
        }

        if ($map === []) {
            $map = ['default' => 'bg/breadcumb-bg.jpg'];
        }

        return $map;
    }
}

if (! function_exists('frontend_breadcrumb_page_key')) {
    function frontend_breadcrumb_page_key(?string $key = null): string
    {
        $slots = frontend_breadcrumb_slot_map();
        if ($key && isset($slots[$key])) {
            return $key;
        }

        $name = request()->route()?->getName() ?? '';
        $name = (string) preg_replace('/^frontend\.(ar\.)?/', '', $name);

        $routeMap = [
            'about' => 'about',
            'services' => 'services',
            'faq' => 'faq',
            'subscription' => 'subscription',
            'contact' => 'contact',
            'blog' => 'blog',
            'blog.show' => 'blog_details',
            'clinics' => 'clinics',
            'clinics.show' => 'clinic_details',
            'doctors' => 'doctors',
            'doctors.show' => 'doctor_details',
            'social' => 'social',
        ];

        $resolved = $routeMap[$name] ?? 'default';

        return isset($slots[$resolved]) ? $resolved : 'default';
    }
}

if (! function_exists('frontend_breadcrumb_image')) {
    function frontend_breadcrumb_image(?string $keyOrFile = null): string
    {
        $slots = frontend_breadcrumb_slot_map();
        $looksLikeFile = $keyOrFile
            && (str_contains($keyOrFile, '/') || preg_match('/\.(jpe?g|png|webp|gif)$/i', $keyOrFile));

        if ($looksLikeFile) {
            $file = $keyOrFile;
        } else {
            $key = frontend_breadcrumb_page_key($keyOrFile);
            $file = $slots[$key] ?? $slots['default'] ?? 'bg/breadcumb-bg.jpg';
        }

        $relative = 'frontend/assets/img/' . ltrim((string) $file, '/');
        $absolute = public_path($relative);
        if (! is_file($absolute) && ! empty($slots['default'])) {
            $relative = 'frontend/assets/img/' . ltrim($slots['default'], '/');
            $absolute = public_path($relative);
        }

        $url = asset($relative);
        if (is_file($absolute)) {
            $url .= '?v=' . filemtime($absolute);
        }

        return $url;
    }
}

if (! function_exists('frontend_bg_style')) {
    function frontend_bg_style(?string $path): string
    {
        $url = frontend_media_url($path);
        if ($url === '') {
            return '';
        }

        return "background-image: url('{$url}');";
    }
}

if (! function_exists('website_social_platforms')) {
    function website_social_platforms(): \Illuminate\Support\Collection
    {
        return app(\App\Services\Frontend\WebsiteLinkCatalog::class)->socialPlatforms();
    }
}

if (! function_exists('website_store_platforms')) {
    function website_store_platforms(): \Illuminate\Support\Collection
    {
        return app(\App\Services\Frontend\WebsiteLinkCatalog::class)->storePlatforms();
    }
}

if (! function_exists('website_store_url')) {
    function website_store_url(string $key, ?string $fallback = null): string
    {
        return app(\App\Services\Frontend\WebsiteLinkCatalog::class)->storeUrl($key, $fallback);
    }
}
