<?php

namespace App\Http\Controllers\AdminPanel\Clinic;

use App\Http\Controllers\Controller;
use App\Services\Deals\DealFinancialReportService;
use Illuminate\Http\Request;

class ClinicDealsFinancialController extends Controller
{
    public function __construct(
        protected DealFinancialReportService $reportService
    ) {
    }

    public function index(Request $request)
    {
        $clinicId = (int) auth()->user()->organizationClinicId();
        $from = $request->input('from');
        $to = $request->input('to');
        $settlementStatus = $request->input('settlement_status');

        $totals = $this->reportService->totals($clinicId, $from, $to);
        $dealRows = $this->reportService->dealRows($clinicId, $from, $to, $settlementStatus);

        return view('clinic.deals_financial.index', compact('totals', 'dealRows', 'from', 'to', 'settlementStatus'));
    }
}
