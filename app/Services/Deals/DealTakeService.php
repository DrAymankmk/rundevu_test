<?php

namespace App\Services\Deals;

use App\Models\ClinicContract;
use App\Models\Deal;
use App\Models\PatientDeal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DealTakeService
{
    public function __construct(
        protected DealAvailabilityService $availabilityService
    ) {
    }

    public function take(Deal $deal, User $patient, bool $allowMultipleUnused = false): PatientDeal
    {
        $this->availabilityService->assertAvailable($deal);

        if (!$allowMultipleUnused) {
            $existingUnused = PatientDeal::where('deal_id', $deal->id)
                ->where('patient_id', $patient->id)
                ->where('used', false)
                ->exists();

            if ($existingUnused) {
                abort(422, __('deals.patient_already_has_unused_deal'));
            }
        }

        return DB::transaction(function () use ($deal, $patient) {
            $deal = Deal::whereKey($deal->id)->lockForUpdate()->firstOrFail();
            $this->availabilityService->assertAvailable($deal);

            $snapshots = $this->buildSnapshots($deal);

            return PatientDeal::create(array_merge([
                'deal_id' => $deal->id,
                'patient_id' => $patient->id,
                'generated_code' => $this->generateUniqueCode(),
                'used' => false,
                'settlement_status' => PatientDeal::SETTLEMENT_PENDING,
            ], $snapshots));
        });
    }

    public function buildSnapshots(Deal $deal): array
    {
        $contract = ClinicContract::where('clinic_id', $deal->clinic_id)->first();
        $type = $contract?->deal_revenue_type;
        $amount = (float) ($contract?->deal_revenue_amount ?? 0);
        $priceAfter = (float) $deal->price_after;

        $platformEntitlement = 0.0;
        if ($type === ClinicContract::DEAL_REVENUE_PERCENTAGE) {
            $platformEntitlement = round($priceAfter * ($amount / 100), 2);
        } elseif ($type === ClinicContract::DEAL_REVENUE_FIXED) {
            $platformEntitlement = round($amount, 2);
        }

        $clinicNet = round(max($priceAfter - $platformEntitlement, 0), 2);

        return [
            'price_after' => $priceAfter,
            'platform_entitlement' => $platformEntitlement,
            'clinic_net' => $clinicNet,
            'deal_revenue_type' => $type,
            'deal_revenue_amount' => $amount,
        ];
    }

    protected function generateUniqueCode(): string
    {
        do {
            $code = 'DL-' . Str::upper(Str::random(8));
        } while (PatientDeal::where('generated_code', $code)->exists());

        return $code;
    }
}
