<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicContract;
use App\Services\FinancialSettlementReportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinancialReportsController extends Controller
{
    public function index(Request $request, FinancialSettlementReportService $reports)
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
            'clinic_id' => ['nullable', 'integer', 'exists:clinics,id'],
            'report_type' => ['nullable', Rule::in([
                ClinicContract::ANNUAL_SUBSCRIPTION,
                ClinicContract::ONLINE_PAYMENT_COMMISSION,
                ClinicContract::CASH_COMMISSION,
            ])],
        ]);

        try {
            [$startDate, $endDate, $month] = $reports->period($validated['month'] ?? null);
        } catch (\Throwable $exception) {
            [$startDate, $endDate, $month] = $reports->period(null);
        }

        $account = auth()->user();
        $isMainAdmin = (int) $account->app_type === 6;
        $isClinicAdmin = in_array((int) $account->app_type, [1, 7, 11], true);

        if (!$isMainAdmin && !$isClinicAdmin) {
            abort(403);
        }

        $clinicId = $isMainAdmin
            ? ($validated['clinic_id'] ?? null)
            : $account->organizationClinicId();
        $reportType = $validated['report_type'] ?? null;

        $clinics = $isMainAdmin
            ? Clinic::whereIn('app_type', config('organization_fields.app_types', [1, 4, 5, 7]))
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();

        $reportTypes = [
            '' => __('financial_reports.all_report_types'),
            ClinicContract::ANNUAL_SUBSCRIPTION => __('financial_reports.annual_subscription'),
            ClinicContract::ONLINE_PAYMENT_COMMISSION => __('financial_reports.online_payment_commission'),
            ClinicContract::CASH_COMMISSION => __('financial_reports.cash_commission'),
        ];

        $totals = $reports->totals($startDate, $endDate, $clinicId, $reportType);
        $clinicSummaries = $reports->clinicSummaries($startDate, $endDate, $clinicId, $reportType);
        $clinicDetailedReport = $clinicId
            ? $reports->clinicDetailedReport($startDate, $endDate, (int) $clinicId, $reportType)
            : null;
        $reservations = $reports->query($startDate, $endDate, $clinicId, $reportType)
            ->latest()
            ->paginate(30)
            ->appends($request->query());

        return view('financial_reports.index', compact(
            'month',
            'clinicId',
            'reportType',
            'reportTypes',
            'clinics',
            'totals',
            'clinicSummaries',
            'clinicDetailedReport',
            'reservations',
            'isMainAdmin'
        ));
    }
}
