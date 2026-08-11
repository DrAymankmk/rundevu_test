<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Reservations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FinancialSettlementReportService
{
    public function period(?string $month): array
    {
        $date = $month
            ? Carbon::createFromFormat('Y-m-d', $month . '-01')
            : now();

        return [
            $date->copy()->startOfMonth(),
            $date->copy()->endOfMonth(),
            $date->format('Y-m'),
        ];
    }

    public function query(Carbon $startDate, Carbon $endDate, ?int $clinicId = null, ?string $reportType = null): Builder
    {
        return Reservations::query()
            ->with(['clinic', 'doctor', 'user'])
            ->where('status_id', 6)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($clinicId, function ($query) use ($clinicId) {
                $query->where('clinic_id', $clinicId);
            })
            ->when($reportType, function ($query) use ($reportType) {
                if ($reportType === 'annual_subscription') {
                    $query->where('settlement_direction', 'annual_subscription_only');
                } elseif ($reportType === 'online_payment_commission') {
                    $query->where('settlement_direction', 'platform_pays_clinic');
                } elseif ($reportType === 'cash_commission') {
                    $query->where('settlement_direction', 'clinic_pays_platform');
                }
            });
    }

    public function totals(Carbon $startDate, Carbon $endDate, ?int $clinicId = null, ?string $reportType = null): array
    {
        $row = (clone $this->query($startDate, $endDate, $clinicId, $reportType))
            ->selectRaw('COUNT(*) as completed_bookings_count')
            ->selectRaw('COALESCE(SUM(price), 0) as total_collected')
            ->selectRaw('COALESCE(SUM(platform_commission_amount), 0) as platform_commission')
            ->selectRaw('COALESCE(SUM(clinic_net_amount), 0) as clinic_net_amount')
            ->selectRaw("COALESCE(SUM(CASE WHEN settlement_direction = 'clinic_pays_platform' THEN platform_commission_amount ELSE 0 END), 0) as clinic_due_to_platform")
            ->selectRaw("COALESCE(SUM(CASE WHEN settlement_direction = 'platform_pays_clinic' THEN clinic_net_amount ELSE 0 END), 0) as platform_due_to_clinic")
            ->selectRaw("COALESCE(SUM(CASE WHEN payment_method = 'online' AND payment_status != 1 THEN price ELSE 0 END), 0) as pending_online_amount")
            ->first();

        return [
            'completed_bookings_count' => (int) $row->completed_bookings_count,
            'total_collected' => round((float) $row->total_collected, 2),
            'platform_commission' => round((float) $row->platform_commission, 2),
            'clinic_net_amount' => round((float) $row->clinic_net_amount, 2),
            'clinic_due_to_platform' => round((float) $row->clinic_due_to_platform, 2),
            'platform_due_to_clinic' => round((float) $row->platform_due_to_clinic, 2),
            'pending_online_amount' => round((float) $row->pending_online_amount, 2),
        ];
    }

    public function clinicSummaries(Carbon $startDate, Carbon $endDate, ?int $clinicId = null, ?string $reportType = null)
    {
        return (clone $this->query($startDate, $endDate, $clinicId, $reportType))
            ->select('clinic_id')
            ->selectRaw('COUNT(*) as completed_bookings_count')
            ->selectRaw('COALESCE(SUM(price), 0) as total_collected')
            ->selectRaw('COALESCE(SUM(platform_commission_amount), 0) as platform_commission')
            ->selectRaw('COALESCE(SUM(clinic_net_amount), 0) as clinic_net_amount')
            ->selectRaw("COALESCE(SUM(CASE WHEN settlement_direction = 'clinic_pays_platform' THEN platform_commission_amount ELSE 0 END), 0) as clinic_due_to_platform")
            ->selectRaw("COALESCE(SUM(CASE WHEN settlement_direction = 'platform_pays_clinic' THEN clinic_net_amount ELSE 0 END), 0) as platform_due_to_clinic")
            ->groupBy('clinic_id')
            ->orderByDesc(DB::raw('SUM(platform_commission_amount)'))
            ->get()
            ->map(function ($row) {
                $clinic = Clinic::find($row->clinic_id);

                return [
                    'clinic_id' => $row->clinic_id,
                    'clinic_name' => $clinic->name ?? '-',
                    'completed_bookings_count' => (int) $row->completed_bookings_count,
                    'total_collected' => round((float) $row->total_collected, 2),
                    'platform_commission' => round((float) $row->platform_commission, 2),
                    'clinic_net_amount' => round((float) $row->clinic_net_amount, 2),
                    'clinic_due_to_platform' => round((float) $row->clinic_due_to_platform, 2),
                    'platform_due_to_clinic' => round((float) $row->platform_due_to_clinic, 2),
                ];
            });
    }

    public function clinicDetailedReport(Carbon $startDate, Carbon $endDate, int $clinicId, ?string $reportType = null): array
    {
        $clinic = Clinic::with('contract')->find($clinicId);
        $totals = $this->totals($startDate, $endDate, $clinicId, $reportType);

        $breakdown = (clone $this->query($startDate, $endDate, $clinicId, $reportType))
            ->select('payment_method', 'settlement_direction')
            ->selectRaw('COUNT(*) as bookings_count')
            ->selectRaw('COALESCE(SUM(price), 0) as total_collected')
            ->selectRaw('COALESCE(SUM(platform_commission_amount), 0) as platform_commission')
            ->selectRaw('COALESCE(SUM(clinic_net_amount), 0) as clinic_net_amount')
            ->groupBy('payment_method', 'settlement_direction')
            ->get()
            ->map(function ($row) {
                return [
                    'payment_method' => $row->payment_method,
                    'settlement_direction' => $row->settlement_direction,
                    'bookings_count' => (int) $row->bookings_count,
                    'total_collected' => round((float) $row->total_collected, 2),
                    'platform_commission' => round((float) $row->platform_commission, 2),
                    'clinic_net_amount' => round((float) $row->clinic_net_amount, 2),
                ];
            });

        $settlementAmount = 0;
        $settlementLabelKey = 'financial_reports.no_settlement_due';

        if ($totals['platform_due_to_clinic'] > 0) {
            $settlementAmount = $totals['platform_due_to_clinic'];
            $settlementLabelKey = 'financial_reports.clinic_due_label';
        } elseif ($totals['clinic_due_to_platform'] > 0) {
            $settlementAmount = $totals['clinic_due_to_platform'];
            $settlementLabelKey = 'financial_reports.platform_due_label';
        }

        return [
            'clinic' => $clinic,
            'contract' => $clinic && $clinic->contract ? $clinic->contract : null,
            'totals' => $totals,
            'breakdown' => $breakdown,
            'settlement_label_key' => $settlementLabelKey,
            'settlement_amount' => round((float) $settlementAmount, 2),
        ];
    }
}
