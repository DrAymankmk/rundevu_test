<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WebsiteVisitLog extends Model
{
    public const PAGE_TYPES = [
        'home',
        'about',
        'services',
        'faq',
        'subscription',
        'contact',
        'blog',
        'blog_show',
        'clinics',
        'clinic_show',
        'doctors',
        'doctor_show',
        'social',
        'other',
    ];

    protected $fillable = [
        'visited_at',
        'ip',
        'session_id',
        'visitor_token',
        'user_agent',
        'is_bot',
        'bot_name',
        'page_url',
        'page_path',
        'page_type',
        'page_title',
        'entity_type',
        'entity_id',
        'entity_slug',
        'referer',
        'referer_host',
        'locale',
        'time_spent_seconds',
        'events',
        'events_count',
        'country',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'is_bot' => 'boolean',
        'events' => 'array',
        'time_spent_seconds' => 'integer',
        'events_count' => 'integer',
        'entity_id' => 'integer',
    ];

    public function scopeHuman(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }

    public function scopeBots(Builder $query): Builder
    {
        return $query->where('is_bot', true);
    }

    public function scopeBetweenDates(Builder $query, $from = null, $to = null): Builder
    {
        if ($from) {
            $query->where('visited_at', '>=', $from);
        }
        if ($to) {
            $query->where('visited_at', '<=', $to);
        }

        return $query;
    }

    public function scopeOfPageType(Builder $query, $types): Builder
    {
        $types = is_array($types) ? $types : [$types];
        $types = array_values(array_filter($types));

        if ($types === []) {
            return $query;
        }

        return $query->whereIn('page_type', $types);
    }

    public function scopeOfEntity(Builder $query, ?string $type, $id = null): Builder
    {
        if ($type) {
            $query->where('entity_type', $type);
        }
        if ($id !== null && $id !== '') {
            $query->where('entity_id', (int) $id);
        }

        return $query;
    }

    public function formattedTimeSpent(): string
    {
        $seconds = (int) ($this->time_spent_seconds ?? 0);
        if ($seconds <= 0) {
            return '—';
        }

        $minutes = intdiv($seconds, 60);
        $remain = $seconds % 60;

        if ($minutes <= 0) {
            return $remain . 's';
        }

        return $minutes . 'm ' . $remain . 's';
    }
}
