<?php

namespace App\Services\Deals;

use App\Models\Clinic;
use App\Models\Deal;
use App\Models\PatientDeal;
use Illuminate\Support\Facades\DB;

class DealConfirmService
{
    public function __construct(
        protected DealTakeService $takeService
    ) {
    }

    public function confirm(PatientDeal $patientDeal, Clinic $staff): PatientDeal
    {
        return DB::transaction(function () use ($patientDeal, $staff) {
            $patientDeal = PatientDeal::whereKey($patientDeal->id)->lockForUpdate()->firstOrFail();

            if ($patientDeal->used) {
                abort(422, __('deals.already_used'));
            }

            $deal = Deal::whereKey($patientDeal->deal_id)->lockForUpdate()->firstOrFail();

            if ($deal->maximum_number_of_usage !== null && $deal->number_of_uses >= $deal->maximum_number_of_usage) {
                abort(422, __('deals.deal_max_usage_reached'));
            }

            if ($patientDeal->price_after === null) {
                $snapshots = $this->takeService->buildSnapshots($deal);
                $patientDeal->fill($snapshots);
            }

            $patientDeal->used = true;
            $patientDeal->used_date_time = now();
            $patientDeal->used_by = $staff->id;
            $patientDeal->save();

            $deal->increment('number_of_uses');

            return $patientDeal->fresh(['deal', 'patient', 'usedBy']);
        });
    }
}
