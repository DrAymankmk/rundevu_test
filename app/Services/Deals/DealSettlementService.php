<?php

namespace App\Services\Deals;

use App\Models\PatientDeal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DealSettlementService
{
    public function markTransferCompleted(array $patientDealIds): int
    {
        return DB::transaction(function () use ($patientDealIds) {
            return PatientDeal::whereIn('id', $patientDealIds)
                ->where('used', true)
                ->where('settlement_status', PatientDeal::SETTLEMENT_PENDING)
                ->update([
                    'settlement_status' => PatientDeal::SETTLEMENT_TRANSFER_COMPLETED,
                    'settled_at' => now(),
                ]);
        });
    }

    public function markClinicPendingAsCompleted(int $clinicId, ?string $from = null, ?string $to = null): int
    {
        $query = PatientDeal::query()
            ->where('used', true)
            ->where('settlement_status', PatientDeal::SETTLEMENT_PENDING)
            ->whereHas('deal', fn ($q) => $q->where('clinic_id', $clinicId));

        if ($from) {
            $query->whereDate('used_date_time', '>=', $from);
        }
        if ($to) {
            $query->whereDate('used_date_time', '<=', $to);
        }

        return $query->update([
            'settlement_status' => PatientDeal::SETTLEMENT_TRANSFER_COMPLETED,
            'settled_at' => now(),
        ]);
    }
}
