<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

/**
 * Defers visit creation to the client beacon so public HTML stays cookie-free
 * and cacheable. Shares bootstrap config for visit-tracker.js.
 */
class LogWebsiteVisit
{
    public function handle(Request $request, Closure $next)
    {
        $shouldTrack = $this->shouldLog($request);

        View::share('websiteVisitLogId', null);
        View::share('websiteVisitLogToken', null);
        View::share('websiteVisitLogDeferred', $shouldTrack);
        View::share('websiteVisitBootstrapEndpoint', $shouldTrack
            ? route('website-visit-logs.bootstrap')
            : null);

        return $next($request);
    }

    private function shouldLog(Request $request): bool
    {
        if (! config('website_visit_logs.enabled', true)) {
            return false;
        }

        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return false;
        }

        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            return false;
        }

        $routeName = (string) optional($request->route())->getName();
        if (Str::endsWith($routeName, 'blog.load-more') || Str::contains($routeName, 'load-more')) {
            return false;
        }

        $path = ltrim($request->path(), '/');
        $pathLower = strtolower($path);

        foreach (config('website_visit_logs.exclude_paths', []) as $excluded) {
            if ($pathLower === strtolower($excluded)) {
                return false;
            }
        }

        foreach (config('website_visit_logs.exclude_path_prefixes', []) as $prefix) {
            $prefix = strtolower(trim($prefix, '/'));
            if ($prefix !== '' && ($pathLower === $prefix || Str::startsWith($pathLower, $prefix . '/'))) {
                return false;
            }
        }

        $extension = strtolower(pathinfo($pathLower, PATHINFO_EXTENSION));
        if ($extension !== '' && in_array($extension, config('website_visit_logs.exclude_extensions', []), true)) {
            return false;
        }

        $ip = (string) $request->ip();
        foreach (config('website_visit_logs.exclude_ip_prefixes', []) as $prefix) {
            if ($prefix !== '' && Str::startsWith($ip, $prefix)) {
                return false;
            }
        }

        return true;
    }
}
