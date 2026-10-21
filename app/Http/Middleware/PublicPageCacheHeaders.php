<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Marks cookie-free public HTML as cacheable at the edge / FastCGI layer.
 */
class PublicPageCacheHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $this->shouldCache($request, $response)) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'public, max-age=60, s-maxage=300, stale-while-revalidate=60');
        $response->headers->remove('Set-Cookie');

        return $response;
    }

    protected function shouldCache(Request $request, $response): bool
    {
        if (! in_array(strtoupper($request->method()), ['GET', 'HEAD'], true)) {
            return false;
        }

        // Any active / resumed session (or inbound session cookie) must not be cached.
        $sessionCookie = config('session.cookie');
        if (
            Session::isStarted()
            || $request->hasSession()
            || ($sessionCookie && $request->cookies->has($sessionCookie))
        ) {
            return false;
        }

        if (! method_exists($response, 'headers')) {
            return false;
        }

        $status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 200;
        if ($status !== 200) {
            return false;
        }

        $routeName = (string) optional($request->route())->getName();
        if ($routeName === '') {
            return false;
        }

        if (! (Str::startsWith($routeName, 'frontend.') || Str::startsWith($routeName, 'frontend.ar.'))) {
            return false;
        }

        if (
            Str::contains($routeName, 'subscription')
            || Str::contains($routeName, 'contact')
            || Str::contains($routeName, 'csrf')
            || Str::contains($routeName, 'book_demo')
        ) {
            return false;
        }

        return true;
    }
}
