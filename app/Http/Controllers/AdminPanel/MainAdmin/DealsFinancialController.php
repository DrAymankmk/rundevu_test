<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\PatientDeal;
use App\Services\Deals\DealFinancialReportService;
use App\Services\Deals\DealSettlementService;
use Illuminate\Http\Request;

class DealsFinancialController extends Controller
{
    public function __construct(
        protected DealFinancialReportService $reportService,
        protected DealSettlementService $settlementService
    ) {
    }

    public function index(Request $request)
    {
        abort_unless((int) auth()->user()->app_type === 6, 403);

        $clinicId = $request->filled('clinic_id') ? (int) $request->clinic_id : null;
        $from = $request->input('from');
        $to = $request->input('to');

        $totals = $this->reportService->totals($clinicId, $from, $to);
        $clinicSummaries = $clinicId
            ? collect([$this->reportService->clinicSummaries($from, $to)->firstWhere('clinic_id', $clinicId)])->filter()
            : $this->reportService->clinicSummaries($from, $to);

        $dealRows = $clinicId
            ? $this->reportService->dealRows($clinicId, $from, $to, $request->input('settlement_status'))
            : collect();

        $pendingPatientDeals = PatientDeal::with(['deal.clinic', 'patient'])
            ->used()
            ->where('settlement_status', PatientDeal::SETTLEMENT_PENDING)
            ->when($clinicId, fn ($q) => $q->whereHas('deal', fn ($d) => $d->where('clinic_id', $clinicId)))
            ->when($from, fn ($q) => $q->whereDate('used_date_time', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('used_date_time', '<=', $to))
            ->orderByDesc('used_date_time')
            ->paginate(20)
            ->withQueryString();

        $clinics = Clinic::where('app_type', 1)->where('status', 1)->orderBy('name')->get(['id', 'name', 'name_en']);

        return view('main_admin.deals_financial.index', compact(
            'totals',
            'clinicSummaries',
            'dealRows',
            'pendingPatientDeals',
            'clinics',
            'clinicId',
            'from',
            'to'
        ));
    }

    public function markSettled(Request $request)
    {
        abort_unless((int) auth()->user()->app_type === 6, 403);

        $request->validate([
            'patient_deal_ids' => ['nullable', 'array'],
            'patient_deal_ids.*' => ['integer', 'exists:patient_deals,id'],
            'clinic_id' => ['nullable', 'integer', 'exists:clinics,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        if ($request->filled('patient_deal_ids')) {
            $count = $this->settlementService->markTransferCompleted($request->patient_deal_ids);
        } elseif ($request->filled('clinic_id')) {
            $count = $this->settlementService->markClinicPendingAsCompleted(
                (int) $request->clinic_id,
                $request->input('from'),
                $request->input('to')
            );
        } else {
            session()->flash('error', __('deals_financial.nothing_to_settle'));

            return redirect()->back();
        }

        session()->flash('success', __('deals_financial.settled', ['count' => $count]));

        return redirect()->back();
    }
}
