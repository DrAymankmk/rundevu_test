<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\WebsiteVisitLog;
use App\Services\WebsiteVisitLogs\BotDetector;
use App\Services\WebsiteVisitLogs\VisitPageResolver;
use App\Services\WebsiteVisitLogs\VisitUrlDisplay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebsiteVisitLogBootstrapController extends Controller
{
    public function __construct(
        private BotDetector $botDetector,
        private VisitPageResolver $pageResolver
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        if (! config('website_visit_logs.enabled', true)) {
            return response()->json(['ok' => false], 404);
        }

        $validated = $request->validate([
            'page_url' => 'nullable|string|max:2048',
            'page_path' => 'nullable|string|max:512',
            'page_title' => 'nullable|string|max:255',
            'referer' => 'nullable|string|max:2048',
        ]);

        try {
            $visitorToken = $this->resolveVisitorToken($request);

            $clientPath = (string) ($validated['page_path'] ?? '');
            $clientUrl = (string) ($validated['page_url'] ?? '');
            $page = ($clientPath !== '' || $clientUrl !== '')
                ? $this->pageResolver->resolveFromClientPath(
                    $clientPath !== '' ? $clientPath : $clientUrl,
                    $clientUrl !== '' ? $clientUrl : null
                )
                : $this->pageResolver->resolve($request);

            $bot = $this->botDetector->detect($request->userAgent());
            $referer = $validated['referer'] ?? $request->headers->get('referer');
            $refererHost = $this->parseRefererHost(is_string($referer) ? $referer : null);

            $sessionId = null;
            if ($request->hasSession() && $request->session()->isStarted()) {
                $sessionId = mb_substr((string) $request->session()->getId(), 0, 64) ?: null;
            }

            $log = WebsiteVisitLog::create([
                'visited_at' => now(),
                'ip' => mb_substr((string) $request->ip(), 0, 45) ?: null,
                'session_id' => $sessionId,
                'visitor_token' => $visitorToken,
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 512) ?: null,
                'is_bot' => $bot['is_bot'],
                'bot_name' => $bot['bot_name'],
                'page_url' => $page['page_url'],
                'page_path' => $page['page_path'],
                'page_type' => $page['page_type'],
                'page_title' => ! empty($validated['page_title'])
                    ? mb_substr($validated['page_title'], 0, 255)
                    : null,
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

            return response()->json([
                'ok' => true,
                'visit_id' => $log->id,
                'visitor_token' => $visitorToken,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Website visit bootstrap failed: ' . $e->getMessage(), [
                'path' => $request->path(),
            ]);

            return response()->json(['ok' => false], 500);
        }
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
