<?php

namespace App\Services\Deals;

use App\Models\Deal;

class DealAvailabilityService
{
    public function isAvailable(Deal $deal): bool
    {
        return $deal->isAvailable();
    }

    public function assertAvailable(Deal $deal): void
    {
        if (!$deal->isAvailable()) {
            abort(422, __('deals.deal_not_available'));
        }
    }
}
