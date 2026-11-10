<?php

namespace App\Services\Deals;

use App\Models\Deal;
use App\Models\PatientDeal;
use Illuminate\Support\Collection;

class DealFinancialReportService
{
    public function totals(?int $clinicId = null, ?string $from = null, ?string $to = null): array
    {
        $soldQuery = PatientDeal::query()->when($clinicId, fn ($q) => $q->whereHas('deal', fn ($d) => $d->where('clinic_id', $clinicId)));
        $this->applyClaimedDateFilter($soldQuery, $from, $to);

        $redeemedQuery = PatientDeal::query()
            ->used()
            ->when($clinicId, fn ($q) => $q->whereHas('deal', fn ($d) => $d->where('clinic_id', $clinicId)));
        $this->applyUsedDateFilter($redeemedQuery, $from, $to);

        $pendingDuesQuery = (clone $redeemedQuery)->where('settlement_status', PatientDeal::SETTLEMENT_PENDING);
        $completedDuesQuery = (clone $redeemedQuery)->where('settlement_status', PatientDeal::SETTLEMENT_TRANSFER_COMPLETED);

        return [
            'total_sales' => (float) (clone $redeemedQuery)->sum('price_after'),
            'deals_sold' => (int) $soldQuery->count(),
            'deals_redeemed' => (int) (clone $redeemedQuery)->count(),
            'platform_entitlements' => (float) (clone $redeemedQuery)->sum('platform_entitlement'),
            'clinic_dues' => (float) (clone $redeemedQuery)->sum('clinic_net'),
            'pending_clinic_dues' => (float) $pendingDuesQuery->sum('clinic_net'),
            'completed_clinic_dues' => (float) $completedDuesQuery->sum('clinic_net'),
            'pending_count' => (int) $pendingDuesQuery->count(),
            'completed_count' => (int) $completedDuesQuery->count(),
        ];
    }

    public function clinicSummaries(?string $from = null, ?string $to = null): Collection
    {
        $clinicIds = Deal::query()->distinct()->pluck('clinic_id');

        return $clinicIds->map(function ($clinicId) use ($from, $to) {
            $totals = $this->totals((int) $clinicId, $from, $to);
            $deal = Deal::with('clinic')->where('clinic_id', $clinicId)->first();

            return [
                'clinic_id' => (int) $clinicId,
                'clinic_name' => $deal?->clinic?->name,
                'totals' => $totals,
            ];
        })->filter(fn ($row) => $row['totals']['deals_sold'] > 0 || $row['totals']['deals_redeemed'] > 0)
            ->values();
    }

    public function dealRows(int $clinicId, ?string $from = null, ?string $to = null, ?string $settlementStatus = null): Collection
    {
        $deals = Deal::with('specialty')
            ->where('clinic_id', $clinicId)
            ->orderByDesc('id')
            ->get();

        return $deals->map(function (Deal $deal) use ($from, $to, $settlementStatus) {
            $redeemed = PatientDeal::query()
                ->where('deal_id', $deal->id)
                ->used();
            $this->applyUsedDateFilter($redeemed, $from, $to);

            if ($settlementStatus) {
                $redeemed->where('settlement_status', $settlementStatus);
            }

            $items = $redeemed->get();
            if ($items->isEmpty() && $settlementStatus) {
                return null;
            }

            $pendingItems = $items->where('settlement_status', PatientDeal::SETTLEMENT_PENDING);
            $completedItems = $items->where('settlement_status', PatientDeal::SETTLEMENT_TRANSFER_COMPLETED);
            $pending = $pendingItems->count();
            $completed = $completedItems->count();

            $status = 'none';
            if ($pending > 0 && $completed > 0) {
                $status = 'mixed';
            } elseif ($pending > 0) {
                $status = PatientDeal::SETTLEMENT_PENDING;
            } elseif ($completed > 0) {
                $status = PatientDeal::SETTLEMENT_TRANSFER_COMPLETED;
            }

            $sample = $items->first();

            return [
                'deal' => $deal,
                'price' => (float) $deal->price_after,
                'platform_type' => $sample?->deal_revenue_type,
                'platform_amount' => (float) ($sample?->deal_revenue_amount ?? 0),
                'platform_entitlements' => (float) $items->sum('platform_entitlement'),
                'clinic_net' => (float) $items->sum('clinic_net'),
                'redeemed_count' => $items->count(),
                'settlement_status' => $status,
                'pending_count' => $pending,
                'completed_count' => $completed,
                'pending_dues' => (float) $pendingItems->sum('clinic_net'),
                'completed_dues' => (float) $completedItems->sum('clinic_net'),
            ];
        })->filter()->values();
    }

    protected function applyClaimedDateFilter($query, ?string $from, ?string $to): void
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
    }

    protected function applyUsedDateFilter($query, ?string $from, ?string $to): void
    {
        if ($from) {
            $query->whereDate('used_date_time', '>=', $from);
        }
        if ($to) {
            $query->whereDate('used_date_time', '<=', $to);
        }
    }
}
