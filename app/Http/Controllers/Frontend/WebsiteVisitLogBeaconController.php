<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\WebsiteVisitLog;
use App\Services\WebsiteVisitLogs\VisitUrlDisplay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebsiteVisitLogBeaconController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        if (! config('website_visit_logs.enabled', true)) {
            return response()->json(['ok' => false], 404);
        }

        $maxEvents = (int) config('website_visit_logs.max_events_per_visit', 50);

        $validated = $request->validate([
            'visit_id' => 'required|integer|exists:website_visit_logs,id',
            'visitor_token' => 'required|string|max:64',
            'time_spent_seconds' => 'nullable|integer|min:0|max:86400',
            'page_title' => 'nullable|string|max:255',
            'events' => 'nullable|array|max:' . $maxEvents,
            'events.*.name' => 'required_with:events|string|max:80',
            'events.*.at' => 'nullable|string|max:40',
            'events.*.meta' => 'nullable|array',
        ]);

        $log = WebsiteVisitLog::query()->findOrFail($validated['visit_id']);

        $cookieName = config('website_visit_logs.cookie_name', 'website_visitor_token');
        $cookieToken = (string) $request->cookie($cookieName, '');
        $token = (string) $validated['visitor_token'];

        if ($log->visitor_token !== $token) {
            return response()->json(['ok' => false, 'message' => 'token mismatch'], 403);
        }

        if ($cookieToken !== '' && $cookieToken !== $token) {
            return response()->json(['ok' => false, 'message' => 'cookie mismatch'], 403);
        }

        $updates = [];

        if (array_key_exists('time_spent_seconds', $validated) && $validated['time_spent_seconds'] !== null) {
            $updates['time_spent_seconds'] = max(
                (int) ($log->time_spent_seconds ?? 0),
                (int) $validated['time_spent_seconds']
            );
        }

        if (! empty($validated['page_title']) && empty($log->page_title)) {
            $updates['page_title'] = mb_substr($validated['page_title'], 0, 255);
        }

        $incoming = $validated['events'] ?? [];
        if (is_array($incoming) && $incoming !== []) {
            $existing = is_array($log->events) ? $log->events : [];
            $merged = $this->withoutNoiseEvents($existing);
            $onceNames = $this->indexedOnceEventNames($merged);

            foreach ($incoming as $event) {
                if (count($merged) >= $maxEvents) {
                    break;
                }
                $name = trim((string) ($event['name'] ?? ''));
                $nameLower = strtolower($name);
                if ($name === '' || $nameLower === 'heartbeat') {
                    continue;
                }

                // Keep logs small: only one leave / form_start / scroll_* per visit.
                if ($this->isOnceOnlyEvent($nameLower) && isset($onceNames[$nameLower])) {
                    continue;
                }

                $merged[] = [
                    'name' => mb_substr($name, 0, 80),
                    'at' => mb_substr((string) ($event['at'] ?? now()->toIso8601String()), 0, 40),
                    'meta' => $this->sanitizeMeta($event['meta'] ?? []),
                ];
                if ($this->isOnceOnlyEvent($nameLower)) {
                    $onceNames[$nameLower] = true;
                }
            }

            $updates['events'] = $merged;
            $updates['events_count'] = count($merged);
        }

        if ($updates !== []) {
            $log->fill($updates);
            $log->save();
        }

        return response()->json(['ok' => true]);
    }

    private function sanitizeMeta($meta): array
    {
        if (! is_array($meta)) {
            return [];
        }

        $clean = [];
        foreach ($meta as $key => $value) {
            $key = mb_substr((string) $key, 0, 40);
            if (is_scalar($value) || $value === null) {
                if (is_string($value)) {
                    if (in_array($key, ['href', 'url'], true)) {
                        $value = VisitUrlDisplay::decode($value) ?: $value;
                    }
                    $clean[$key] = mb_substr($value, 0, 255);
                } else {
                    $clean[$key] = $value;
                }
            }
        }

        return $clean;
    }

    private function isOnceOnlyEvent(string $nameLower): bool
    {
        return $nameLower === 'leave'
            || substr($nameLower, -11) === '_form_start'
            || strpos($nameLower, 'scroll_') === 0;
    }

    private function indexedOnceEventNames(array $events): array
    {
        $names = [];
        foreach ($events as $event) {
            $name = strtolower(trim((string) ($event['name'] ?? '')));
            if ($name !== '' && $this->isOnceOnlyEvent($name)) {
                $names[$name] = true;
            }
        }

        return $names;
    }

    private function withoutNoiseEvents(array $events): array
    {
        $cleaned = [];
        $onceSeen = [];

        foreach ($events as $event) {
            $name = strtolower(trim((string) ($event['name'] ?? '')));
            if ($name === '' || $name === 'heartbeat') {
                continue;
            }
            if ($this->isOnceOnlyEvent($name)) {
                if (isset($onceSeen[$name])) {
                    continue;
                }
                $onceSeen[$name] = true;
            }
            $cleaned[] = $event;
        }

        return $cleaned;
    }
}
