<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\WebsiteVisitLog;
use App\Services\WebsiteVisitLogs\VisitStatsService;
use App\Services\WebsiteVisitLogs\VisitUrlDisplay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteVisitLogController extends Controller
{
    public function __construct(private VisitStatsService $statsService)
    {
    }

    public function index(Request $request)
    {
        $this->authorizeMainAdmin();

        $filters = $this->defaultFilters($request);
        $stats = $this->statsService->build($request->merge($filters));
        $pageTypes = WebsiteVisitLog::PAGE_TYPES;

        return view('main_admin.website_visit_logs.index', compact('filters', 'stats', 'pageTypes'));
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorizeMainAdmin();

        $query = $this->statsService->applyFilters(WebsiteVisitLog::query(), $request);

        $totalRecords = WebsiteVisitLog::count();
        $filteredRecords = (clone $query)->count();

        $sortable = [
            'id' => 'id',
            'visited_at' => 'visited_at',
            'ip' => 'ip',
            'page_path' => 'page_path',
            'page_type' => 'page_type',
            'time_spent_seconds' => 'time_spent_seconds',
            'events_count' => 'events_count',
            'referer_host' => 'referer_host',
            'locale' => 'locale',
            'is_bot' => 'is_bot',
        ];

        if ($request->has('order')) {
            $columnIndex = (int) ($request->order[0]['column'] ?? 0);
            $columnName = $request->columns[$columnIndex]['data'] ?? 'visited_at';
            $direction = ($request->order[0]['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
            if (isset($sortable[$columnName])) {
                $query->orderBy($sortable[$columnName], $direction);
            } else {
                $query->orderByDesc('visited_at');
            }
        } else {
            $query->orderByDesc('visited_at');
        }

        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('ip', 'like', "%{$search}%")
                    ->orWhere('page_path', 'like', "%{$search}%")
                    ->orWhere('page_type', 'like', "%{$search}%")
                    ->orWhere('entity_slug', 'like', "%{$search}%")
                    ->orWhere('referer_host', 'like', "%{$search}%")
                    ->orWhere('bot_name', 'like', "%{$search}%");
            });
            $filteredRecords = (clone $query)->count();
        }

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 25);
        if ($length < 1) {
            $length = 25;
        }

        $rows = $query->skip($start)->take($length)->get();

        $data = $rows->map(function (WebsiteVisitLog $log) {
            return [
                'id' => $log->id,
                'visited_at' => optional($log->visited_at)->format('Y-m-d H:i:s'),
                'is_bot' => (bool) $log->is_bot,
                'bot_name' => $log->bot_name,
                'visitor_label' => $log->is_bot
                    ? (__('website_visit_logs.bot') . ($log->bot_name ? ': ' . $log->bot_name : ''))
                    : __('website_visit_logs.human'),
                'ip' => $log->ip,
                'page_path' => VisitUrlDisplay::decode($log->page_path) ?: $log->page_path,
                'page_type' => $log->page_type,
                'page_type_label' => __('website_visit_logs.page_types.' . $log->page_type),
                'entity' => $this->entityLabel($log),
                'time_spent_seconds' => (int) ($log->time_spent_seconds ?? 0),
                'time_spent' => $log->formattedTimeSpent(),
                'events_count' => count($this->visibleEvents($log)),
                'referer_host' => VisitUrlDisplay::decode($log->referer_host) ?: ($log->referer_host ?: '—'),
                'locale' => $log->locale ?: '—',
                'show_url' => route('website-visit-logs.show', $log->id),
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $this->authorizeMainAdmin();

        return response()->json($this->statsService->build($request));
    }

    public function show(Request $request, $id)
    {
        $this->authorizeMainAdmin();

        $log = WebsiteVisitLog::query()->findOrFail($id);

        if ($request->ajax() || $request->wantsJson() || $request->boolean('modal')) {
            $entity = '—';
            if ($log->entity_type) {
                $entity = __('website_visit_logs.entity_types.' . $log->entity_type);
                if ($log->entity_slug) {
                    $entity .= ': ' . (VisitUrlDisplay::decode($log->entity_slug) ?: $log->entity_slug);
                }
                if ($log->entity_id) {
                    $entity .= ' (#' . $log->entity_id . ')';
                }
            }

            return response()->json([
                'id' => $log->id,
                'visited_at' => optional($log->visited_at)->format('Y-m-d H:i:s'),
                'is_bot' => (bool) $log->is_bot,
                'visitor_label' => $log->is_bot
                    ? (__('website_visit_logs.bot') . ($log->bot_name ? ': ' . $log->bot_name : ''))
                    : __('website_visit_logs.human'),
                'ip' => $log->ip ?: '—',
                'locale' => $log->locale ?: '—',
                'page_path' => VisitUrlDisplay::decode($log->page_path) ?: $log->page_path,
                'page_type_label' => __('website_visit_logs.page_types.' . $log->page_type),
                'page_url' => VisitUrlDisplay::decode($log->page_url) ?: $log->page_url,
                'page_title' => $log->page_title ?: '—',
                'entity' => $entity,
                'time_spent' => $log->formattedTimeSpent(),
                'referer' => VisitUrlDisplay::decode($log->referer) ?: ($log->referer ?: '—'),
                'referer_host' => VisitUrlDisplay::decode($log->referer_host) ?: ($log->referer_host ?: '—'),
                'user_agent' => $log->user_agent ?: '—',
                'session_id' => $log->session_id ?: '—',
                'visitor_token' => $log->visitor_token ?: '—',
                'events' => ($visibleEvents = $this->presentEvents($log)),
                'events_count' => count($visibleEvents),
            ]);
        }

        $events = $this->presentEvents($log);

        return view('main_admin.website_visit_logs.show', compact('log', 'events'));
    }

    public function destroy($id): JsonResponse
    {
        $this->authorizeMainAdmin();

        WebsiteVisitLog::query()->findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => __('website_visit_logs.deleted'),
        ]);
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        $this->authorizeMainAdmin();

        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:website_visit_logs,id',
        ]);

        $deleted = WebsiteVisitLog::query()->whereIn('id', $validated['ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => __('website_visit_logs.bulk_deleted', ['count' => $deleted]),
        ]);
    }

    private function authorizeMainAdmin(): void
    {
        abort_unless(Auth::check() && (int) Auth::user()->app_type === 6, 403);
    }

    private function defaultFilters(Request $request): array
    {
        return [
            'date_from' => $request->input('date_from', now()->subDays(29)->toDateString()),
            'date_to' => $request->input('date_to', now()->toDateString()),
            'visitor_type' => $request->input('visitor_type', 'all'),
            'bot_name' => $request->input('bot_name'),
            'page_type' => $request->input('page_type', []),
            'page_path' => $request->input('page_path'),
            'entity_type' => $request->input('entity_type'),
            'entity_slug' => $request->input('entity_slug'),
            'ip' => $request->input('ip'),
            'referer_host' => $request->input('referer_host'),
            'locale' => $request->input('locale'),
            'min_time_spent' => $request->input('min_time_spent'),
            'has_events' => $request->input('has_events'),
        ];
    }

    private function entityLabel(WebsiteVisitLog $log): string
    {
        if (! $log->entity_type) {
            return '—';
        }

        $parts = [__('website_visit_logs.entity_types.' . $log->entity_type)];
        if ($log->entity_slug) {
            $parts[] = VisitUrlDisplay::decode($log->entity_slug) ?: $log->entity_slug;
        } elseif ($log->entity_id) {
            $parts[] = '#' . $log->entity_id;
        }

        return implode(': ', $parts);
    }

    private function visibleEvents(WebsiteVisitLog $log): array
    {
        $events = is_array($log->events) ? $log->events : [];
        $visible = [];
        $onceSeen = [];

        foreach ($events as $event) {
            $name = strtolower(trim((string) ($event['name'] ?? '')));
            if ($name === '' || $name === 'heartbeat') {
                continue;
            }

            $onceOnly = $name === 'leave'
                || strpos($name, 'scroll_') === 0
                || substr($name, -11) === '_form_start';

            if ($onceOnly) {
                if (isset($onceSeen[$name])) {
                    continue;
                }
                $onceSeen[$name] = true;
            }

            $visible[] = $event;
        }

        return $visible;
    }

    private function presentEvents(WebsiteVisitLog $log): array
    {
        return array_map(function (array $event) {
            $name = strtolower(trim((string) ($event['name'] ?? '')));
            $meta = is_array($event['meta'] ?? null) ? $event['meta'] : [];
            $at = (string) ($event['at'] ?? '');

            return [
                'name' => $name,
                'label' => $this->eventLabel($name, $meta),
                'summary' => $this->eventSummary($name, $meta),
                'at' => $at,
                'at_label' => $this->formatEventTime($at),
                'icon' => $this->eventIcon($name),
                'tone' => $this->eventTone($name),
                'details' => $this->eventDetails($meta),
            ];
        }, $this->visibleEvents($log));
    }

    private function eventLabel(string $name, array $meta): string
    {
        if ($name !== '' && \Illuminate\Support\Facades\Lang::has('website_visit_logs.event_names.' . $name)) {
            return __('website_visit_logs.event_names.' . $name);
        }

        if (! empty($meta['visit_event'])) {
            $custom = strtolower(trim((string) $meta['visit_event']));
            if (\Illuminate\Support\Facades\Lang::has('website_visit_logs.event_names.' . $custom)) {
                return __('website_visit_logs.event_names.' . $custom);
            }
        }

        if (str_starts_with($name, 'scroll_')) {
            $percent = (int) (preg_replace('/\D/', '', $name) ?: ($meta['percent'] ?? 0));

            return __('website_visit_logs.event_summaries.scroll_percent', ['percent' => $percent]);
        }

        return $name !== ''
            ? ucwords(str_replace(['_', '-'], ' ', $name))
            : '—';
    }

    private function eventSummary(string $name, array $meta): string
    {
        if (str_starts_with($name, 'scroll_') || in_array($name, ['contact_form_start', 'subscription_form_start'], true)) {
            return '';
        }

        if ($name === 'leave') {
            $reason = strtolower(trim((string) ($meta['reason'] ?? '')));
            if ($reason === '') {
                return '';
            }
            $reasonLabel = \Illuminate\Support\Facades\Lang::has('website_visit_logs.event_reasons.' . $reason)
                ? __('website_visit_logs.event_reasons.' . $reason)
                : $reason;

            return __('website_visit_logs.event_summaries.leave_reason', ['reason' => $reasonLabel]);
        }

        $text = trim((string) ($meta['text'] ?? ''));
        $href = trim((string) (VisitUrlDisplay::decode($meta['href'] ?? null) ?: ($meta['href'] ?? '')));

        if ($text !== '') {
            return __('website_visit_logs.event_summaries.clicked_link', ['text' => \Illuminate\Support\Str::limit($text, 80)]);
        }

        if ($href !== '') {
            return __('website_visit_logs.event_summaries.clicked_href', ['href' => \Illuminate\Support\Str::limit($href, 80)]);
        }

        return '';
    }

    private function eventDetails(array $meta): array
    {
        $details = [];
        foreach ($meta as $key => $value) {
            $key = (string) $key;
            if (in_array($key, ['text', 'href', 'percent', 'reason', 'visit_event'], true)) {
                continue;
            }
            if (! is_scalar($value) && $value !== null) {
                continue;
            }
            $label = \Illuminate\Support\Facades\Lang::has('website_visit_logs.event_meta_keys.' . $key)
                ? __('website_visit_logs.event_meta_keys.' . $key)
                : ucwords(str_replace(['_', '-'], ' ', $key));
            $details[] = [
                'label' => $label,
                'value' => $value === null || $value === '' ? '—' : (string) $value,
            ];
        }

        if (! empty($meta['href'])) {
            $details[] = [
                'label' => __('website_visit_logs.event_meta_keys.href'),
                'value' => (string) (VisitUrlDisplay::decode((string) $meta['href']) ?: $meta['href']),
                'is_url' => true,
            ];
        }

        return $details;
    }

    private function formatEventTime(string $at): string
    {
        if ($at === '') {
            return '—';
        }

        try {
            return \Carbon\Carbon::parse($at)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return $at;
        }
    }

    private function eventIcon(string $name): string
    {
        if (str_starts_with($name, 'scroll_')) {
            return 'ti-arrow-down';
        }

        return match ($name) {
            'leave' => 'ti-logout',
            'contact_form_start', 'subscription_form_start' => 'ti-forms',
            'click_link', 'click_website_link' => 'ti-link',
            'click_cta', 'book_demo' => 'ti-click',
            'open_whatsapp' => 'ti-brand-whatsapp',
            default => 'ti-activity',
        };
    }

    private function eventTone(string $name): string
    {
        if (str_starts_with($name, 'scroll_')) {
            return 'info';
        }

        return match ($name) {
            'leave' => 'secondary',
            'contact_form_start', 'subscription_form_start' => 'warning',
            'click_link', 'click_website_link', 'click_cta', 'book_demo', 'open_whatsapp' => 'success',
            default => 'primary',
        };
    }
}
