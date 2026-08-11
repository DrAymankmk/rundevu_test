<?php

namespace App\Console\Commands;

use App\Models\LoyaltyPointTransaction;
use Illuminate\Console\Command;

class ExpireLoyaltyPoints extends Command
{
    protected $signature = 'loyalty:expire-points';

    protected $description = 'Mark expired loyalty point transactions as expired.';

    public function handle()
    {
        $expired = LoyaltyPointTransaction::where('status', 1)
            ->where('points', '>', 0)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update([
                'status' => 0,
                'expired_at' => now(),
            ]);

        $this->info("Expired loyalty transactions: {$expired}");

        return 0;
    }
}
