<?php

namespace App\Console\Commands;

use App\Models\Deal;
use Illuminate\Console\Command;

class DeactivateExpiredDeals extends Command
{
    protected $signature = 'deals:deactivate-expired';

    protected $description = 'Set expired active deals to not_active';

    public function handle(): int
    {
        $count = Deal::deactivateExpired();
        $this->info("Deactivated {$count} expired deal(s).");

        return self::SUCCESS;
    }
}
