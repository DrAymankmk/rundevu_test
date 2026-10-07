<?php

namespace App\Http\Middleware;

use App\Models\WebsiteVisitLog;
use App\Services\WebsiteVisitLogs\BotDetector;
use App\Services\WebsiteVisitLogs\VisitPageResolver;
use App\Services\WebsiteVisitLogs\VisitUrlDisplay;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class LogWebsiteVisit
{
    public function __construct(
        private BotDetector $botDetector,
        private VisitPageResolver $pageResolver
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        $visitId = null;
        $visitorToken = null;

        if ($this->shouldLog($request)) {
            try {
                $visitorToken = $this->resolveVisitorToken($request);
                $page = $this->pageResolver->resolve($request);
                $bot = $this->botDetector->detect($request->userAgent());
                $referer = $request->headers->get('referer');
                $refererHost = $this->parseRefererHost($referer);

                $log = WebsiteVisitLog::create([
                    'visited_at' => now(),
                    'ip' => mb_substr((string) $request->ip(), 0, 45) ?: null,
                    'session_id' => mb_substr((string) $request->session()->getId(), 0, 64) ?: null,
                    'visitor_token' => $visitorToken,
                    'user_agent' => mb_substr((string) $request->userAgent(), 0, 512) ?: null,
                    'is_bot' => $bot['is_bot'],
                    'bot_name' => $bot['bot_name'],
                    'page_url' => $page['page_url'],
                    'page_path' => $page['page_path'],
                    'page_type' => $page['page_type'],
                    'page_title' => null,
                    'entity_type' => $page['entity_type'],
                    'entity_id' => $page['entity_id'],
                    'entity_slug' => $page['entity_slug'],
                    'referer' => $referer
                        ? mb_substr(VisitUrlDisplay::decode($referer) ?: $referer, 0, 2048)
                        : null,
                    'referer_host' => $refererHost,
                    'locale' => $page['locale'],
                    'time_spent_seconds' => null,
                    'events' => [],
                    'events_count' => 0,
                ]);

                $visitId = $log->id;

                Cookie::queue(cookie(
                    config('website_visit_logs.cookie_name', 'website_visitor_token'),
                    $visitorToken,
                    (int) config('website_visit_logs.cookie_minutes', 525600),
                    '/',
                    null,
                    $request->isSecure(),
                    true,
                    false,
                    'Lax'
                ));
            } catch (\Throwable $e) {
                Log::warning('Website visit log failed: ' . $e->getMessage(), [
                    'path' => $request->path(),
                ]);
            }
        }

        View::share('websiteVisitLogId', $visitId);
        View::share('websiteVisitLogToken', $visitorToken);

        $response = $next($request);

        if ($visitId && method_exists($response, 'headers')) {
            $response->headers->set('X-Visit-Log-Id', (string) $visitId);
        }

        return $response;
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

    private function resolveVisitorToken(Request $request): string
    {
        $cookieName = config('website_visit_logs.cookie_name', 'website_visitor_token');
        $existing = (string) $request->cookie($cookieName, '');

        if ($existing !== '' && preg_match('/^[A-Za-z0-9\-]{16,64}$/', $existing)) {
            return $existing;
        }

        return (string) Str::uuid();
    }

    private function parseRefererHost(?string $referer): ?string
    {
        if (! $referer) {
            return null;
        }

        $host = parse_url($referer, PHP_URL_HOST);
        if (! is_string($host) || $host === '') {
            return null;
        }

        return mb_substr($host, 0, 255);
    }
}
