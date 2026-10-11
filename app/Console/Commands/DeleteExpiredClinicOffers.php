<?php

namespace App\Console\Commands;

use App\Models\ClinicOffer;
use Illuminate\Console\Command;

class DeleteExpiredClinicOffers extends Command
{
    protected $signature = 'clinic-offers:delete-expired';

    protected $description = 'Soft delete clinic offers whose end date has passed.';

    public function handle()
    {
        $deleted = ClinicOffer::deleteExpired();

        $this->info("Deleted expired clinic offers: {$deleted}");

        return Command::SUCCESS;
    }
}
