<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Str;

/**
 * Starts the session only when the request needs flash data, auth, or CSRF.
 * Public frontend GETs stay cookie-free so HTML can be edge / FastCGI cached.
 */
class StartSessionWhenNeeded
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldStartSession($request)) {
            return app(StartSession::class)->handle($request, $next);
        }

        return $next($request);
    }

    protected function shouldStartSession(Request $request): bool
    {
        if (! in_array(strtoupper($request->method()), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return true;
        }

        // Resume an existing session (flash after book-demo, CSRF cookie, etc.)
        // without forcing a new session cookie on cold public GETs.
        $sessionCookie = config('session.cookie');
        if ($sessionCookie && $request->cookies->has($sessionCookie)) {
            return true;
        }

        $path = ltrim($request->path(), '/');
        $pathLower = strtolower($path);

        if ($pathLower === 'csrf-token' || Str::startsWith($pathLower, 'csrf-token/')) {
            return true;
        }

        // Admin, clinic panel, APIs that rely on session auth, language switch helpers.
        if (
            Str::startsWith($pathLower, 'admin')
            || Str::startsWith($pathLower, 'clinic')
            || Str::startsWith($pathLower, 'reception')
            || Str::startsWith($pathLower, 'api')
            || Str::startsWith($pathLower, 'sanctum')
            || Str::startsWith($pathLower, 'changelanguageadmin')
            || $pathLower === 'login'
            || $pathLower === 'register'
            || Str::startsWith($pathLower, 'password')
            || Str::startsWith($pathLower, 'email')
        ) {
            return true;
        }

        $routeName = (string) optional($request->route())->getName();

        // Form pages that render @csrf / old() / validation flash.
        if (
            Str::contains($routeName, 'subscription')
            || Str::contains($routeName, 'contact')
            || Str::endsWith($routeName, 'csrf')
            || $routeName === 'frontend.csrf'
            || $routeName === 'frontend.ar.csrf'
        ) {
            return true;
        }

        // Named frontend marketing pages: no session on safe GETs.
        if ($routeName !== '' && (
            Str::startsWith($routeName, 'frontend.')
            || Str::startsWith($routeName, 'frontend.ar.')
        )) {
            return false;
        }

        // Unnamed public-looking paths under /ar or root marketing URLs.
        if ($this->looksLikePublicFrontendPath($pathLower)) {
            return false;
        }

        return true;
    }

    protected function looksLikePublicFrontendPath(string $pathLower): bool
    {
        $publicPrefixes = [
            '',
            'ar',
            'about',
            'services',
            'faq',
            'blog',
            'clinics',
            'doctors',
            'social-media',
            'sitemap.xml',
            'robots.txt',
        ];

        if (in_array($pathLower, $publicPrefixes, true)) {
            return true;
        }

        foreach (['about', 'services', 'faq', 'blog', 'clinics', 'doctors', 'social-media'] as $segment) {
            if ($pathLower === $segment || Str::startsWith($pathLower, $segment . '/')) {
                return true;
            }
            if ($pathLower === 'ar/' . $segment || Str::startsWith($pathLower, 'ar/' . $segment . '/')) {
                return true;
            }
        }

        // Arabic flat blog slug: /ar/{slug}
        if (Str::startsWith($pathLower, 'ar/') && ! Str::startsWith($pathLower, 'ar/subscription') && ! Str::startsWith($pathLower, 'ar/contact')) {
            $rest = substr($pathLower, 3);
            if ($rest !== '' && ! Str::contains($rest, '/')) {
                return true;
            }
        }

        return false;
    }
}
