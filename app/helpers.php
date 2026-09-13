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
