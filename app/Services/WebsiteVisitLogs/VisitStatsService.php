<?php

namespace App\Services\WebsiteVisitLogs;

use App\Models\BlogPost;
use App\Models\Clinic;
use App\Models\WebsiteVisitLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VisitStatsService
{
    public function applyFilters(Builder $query, Request $request): Builder
    {
        $from = $request->input('date_from');
        $to = $request->input('date_to');

        if ($from) {
            $query->where('visited_at', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $query->where('visited_at', '<=', $to . ' 23:59:59');
        }

        $visitorType = $request->input('visitor_type', 'all');
        if ($visitorType === 'human') {
            $query->where('is_bot', false);
        } elseif ($visitorType === 'bot') {
            $query->where('is_bot', true);
        }

        if ($request->filled('bot_name')) {
            $query->where('bot_name', 'like', '%' . $request->input('bot_name') . '%');
        }

        $pageTypes = $request->input('page_type', []);
        if (! is_array($pageTypes)) {
            $pageTypes = $pageTypes !== null && $pageTypes !== '' ? [$pageTypes] : [];
        }
        $pageTypes = array_values(array_filter($pageTypes));
        if ($pageTypes !== []) {
            $query->whereIn('page_type', $pageTypes);
        }

        if ($request->filled('page_path')) {
            $query->where('page_path', 'like', '%' . $request->input('page_path') . '%');
        }

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->input('entity_type'));
        }

        if ($request->filled('entity_slug')) {
            $query->where('entity_slug', 'like', '%' . $request->input('entity_slug') . '%');
        }

        if ($request->filled('ip')) {
            $query->where('ip', 'like', $request->input('ip') . '%');
        }

        if ($request->filled('referer_host')) {
            $query->where('referer_host', 'like', '%' . $request->input('referer_host') . '%');
        }

        if ($request->filled('locale') && in_array($request->input('locale'), ['en', 'ar'], true)) {
            $query->where('locale', $request->input('locale'));
        }

        if ($request->filled('min_time_spent')) {
            $query->where('time_spent_seconds', '>=', (int) $request->input('min_time_spent'));
        }

        $hasEvents = $request->input('has_events');
        if ($hasEvents === 'yes') {
            $query->where('events_count', '>', 0);
        } elseif ($hasEvents === 'no') {
            $query->where('events_count', '=', 0);
        }

        return $query;
    }

    public function build(Request $request): array
    {
        $base = $this->applyFilters(WebsiteVisitLog::query(), $request);

        $totals = (clone $base)->selectRaw('
            COUNT(*) as visits,
            COUNT(DISTINCT ip) as unique_ips,
            SUM(CASE WHEN is_bot = 0 THEN 1 ELSE 0 END) as humans,
            SUM(CASE WHEN is_bot = 1 THEN 1 ELSE 0 END) as bots,
            AVG(time_spent_seconds) as avg_time_spent
        ')->first();

        return [
            'totals' => [
                'visits' => (int) ($totals->visits ?? 0),
                'unique_ips' => (int) ($totals->unique_ips ?? 0),
                'humans' => (int) ($totals->humans ?? 0),
                'bots' => (int) ($totals->bots ?? 0),
                'avg_time_spent' => (int) round((float) ($totals->avg_time_spent ?? 0)),
            ],
            'pages' => $this->topGrouped((clone $base), 'page_path', 10),
            'doctors' => $this->topEntities((clone $base), 'doctor', 10),
            'clinics' => $this->topEntities((clone $base), 'clinic', 10),
            'blog_posts' => $this->topEntities((clone $base), 'blog_post', 10),
            'referrers' => $this->topGrouped((clone $base)->whereNotNull('referer_host')->where('referer_host', '!=', ''), 'referer_host', 10),
            'links' => $this->topLinkClicks((clone $base)->where('events_count', '>', 0), 10),
            'daily' => $this->dailyCounts((clone $base), $request),
        ];
    }

    private function topGrouped(Builder $query, string $column, int $limit): array
    {
        return $query
            ->select($column . ' as label', DB::raw('COUNT(*) as total'))
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'label' => (string) (VisitUrlDisplay::decode((string) $row->label) ?: $row->label),
                'total' => (int) $row->total,
            ])
            ->all();
    }

    private function topEntities(Builder $query, string $entityType, int $limit): array
    {
        $rows = $query
            ->select('entity_id', 'entity_slug', DB::raw('COUNT(*) as total'))
            ->where('entity_type', $entityType)
            ->whereNotNull('entity_id')
            ->groupBy('entity_id', 'entity_slug')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        $names = $this->resolveEntityNames($entityType, $rows->pluck('entity_id')->filter()->unique()->values());

        return $rows->map(function ($row) use ($names) {
            $id = (int) $row->entity_id;
            $slug = VisitUrlDisplay::decode((string) ($row->entity_slug ?? '')) ?: (string) ($row->entity_slug ?? '');

            return [
                'label' => $names[$id] ?? ($slug !== '' ? $slug : ('#' . $id)),
                'slug' => $slug,
                'entity_id' => $id,
                'total' => (int) $row->total,
            ];
        })->all();
    }

    private function resolveEntityNames(string $entityType, Collection $ids): array
    {
        if ($ids->isEmpty()) {
            return [];
        }

        if (in_array($entityType, ['doctor', 'clinic'], true)) {
            return Clinic::query()
                ->whereIn('id', $ids->all())
                ->pluck('name', 'id')
                ->map(fn ($name) => (string) $name)
                ->all();
        }

        if ($entityType === 'blog_post') {
            return BlogPost::query()
                ->whereIn('id', $ids->all())
                ->pluck('name', 'id')
                ->map(fn ($name) => (string) $name)
                ->all();
        }

        return [];
    }

    private function topLinkClicks(Builder $query, int $limit): array
    {
        $counts = [];

        $query->orderByDesc('id')->limit(500)->get(['events'])->each(function (WebsiteVisitLog $log) use (&$counts) {
            foreach ($log->events ?? [] as $event) {
                $name = $event['name'] ?? '';
                if (! in_array($name, ['click_website_link', 'click_link', 'click_cta'], true)) {
                    continue;
                }
                $meta = $event['meta'] ?? [];
                $href = VisitUrlDisplay::decode($meta['href'] ?? null) ?: ($meta['href'] ?? null);
                $key = $meta['link_id'] ?? $href;
                if (! $key) {
                    continue;
                }
                $label = $meta['text'] ?? $href ?? (string) $key;
                $bucket = (string) $key;
                if (! isset($counts[$bucket])) {
                    $counts[$bucket] = ['label' => (string) $label, 'total' => 0];
                }
                $counts[$bucket]['total']++;
            }
        });

        uasort($counts, fn ($a, $b) => $b['total'] <=> $a['total']);

        return array_values(array_slice($counts, 0, $limit));
    }

    private function dailyCounts(Builder $query, Request $request): array
    {
        $from = $request->input('date_from') ?: now()->subDays(29)->toDateString();
        $to = $request->input('date_to') ?: now()->toDateString();

        $rows = $query
            ->select(DB::raw('DATE(visited_at) as day'), DB::raw('COUNT(*) as total'))
            ->where('visited_at', '>=', $from . ' 00:00:00')
            ->where('visited_at', '<=', $to . ' 23:59:59')
            ->groupBy(DB::raw('DATE(visited_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $values = [];
        $cursor = \Carbon\Carbon::parse($from)->startOfDay();
        $end = \Carbon\Carbon::parse($to)->startOfDay();

        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $labels[] = $key;
            $values[] = (int) optional($rows->get($key))->total;
            $cursor->addDay();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
