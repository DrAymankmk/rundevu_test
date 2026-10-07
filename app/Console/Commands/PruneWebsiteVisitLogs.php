<?php

namespace App\Console\Commands;

use App\Models\WebsiteVisitLog;
use Illuminate\Console\Command;

class PruneWebsiteVisitLogs extends Command
{
    protected $signature = 'website-visit-logs:prune {--days=}';

    protected $description = 'Delete website visit logs older than the configured retention period.';

    public function handle()
    {
        $days = (int) ($this->option('days') ?: config('website_visit_logs.retention_days', 180));
        if ($days < 1) {
            $this->warn('Retention days must be at least 1.');

            return 1;
        }

        $cutoff = now()->subDays($days);
        $deleted = WebsiteVisitLog::query()
            ->where('visited_at', '<', $cutoff)
            ->delete();

        $this->info("Deleted {$deleted} website visit log(s) older than {$days} days.");

        return 0;
    }
}
