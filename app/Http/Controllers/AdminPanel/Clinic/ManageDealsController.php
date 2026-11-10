<?php

namespace App\Http\Controllers\AdminPanel\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\PatientDeal;
use App\Models\User;
use App\Services\Deals\DealConfirmService;
use App\Services\Deals\DealTakeService;
use Illuminate\Http\Request;

class ManageDealsController extends Controller
{
    public function __construct(
        protected DealConfirmService $confirmService,
        protected DealTakeService $takeService
    ) {
    }

    protected function clinicId(): int
    {
        return (int) auth()->user()->organizationClinicId();
    }

    public function index(Request $request)
    {
        $clinicId = $this->clinicId();

        $query = PatientDeal::with(['deal.specialty', 'patient', 'usedBy'])
            ->whereHas('deal', fn ($q) => $q->where('clinic_id', $clinicId));

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('generated_code', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('mobile_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('deal', function ($d) use ($search) {
                        $d->where('title_ar', 'like', "%{$search}%")
                            ->orWhere('title_en', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('used')) {
            $query->where('used', $request->used === '1');
        }

        if ($request->filled('settlement_status')) {
            $query->where('settlement_status', $request->settlement_status);
        }

        if ($request->filled('deal_id')) {
            $query->where('deal_id', $request->deal_id);
        }

        if ($request->filled('specialty_id')) {
            $query->whereHas('deal', fn ($q) => $q->where('specialty_id', $request->specialty_id));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $patientDeals = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $deals = Deal::where('clinic_id', $clinicId)->orderByDesc('id')->get(['id', 'title_ar', 'title_en', 'status']);

        return view('clinic.manage_deals.index', compact('patientDeals', 'deals'));
    }

    public function show($id)
    {
        $patientDeal = PatientDeal::with(['deal.specialty', 'deal.clinic', 'patient', 'usedBy'])
            ->whereHas('deal', fn ($q) => $q->where('clinic_id', $this->clinicId()))
            ->findOrFail($id);

        return view('clinic.manage_deals.show', compact('patientDeal'));
    }

    public function confirm($id)
    {
        $patientDeal = PatientDeal::whereHas('deal', fn ($q) => $q->where('clinic_id', $this->clinicId()))
            ->findOrFail($id);

        try {
            $this->confirmService->confirm($patientDeal, auth()->user());
            session()->flash('success', __('deals.confirmed'));
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    public function assignForm()
    {
        $clinicId = $this->clinicId();
        $deals = Deal::available()->where('clinic_id', $clinicId)->orderByDesc('id')->get();

        return view('clinic.manage_deals.assign', compact('deals'));
    }

    public function assign(Request $request)
    {
        $clinicId = $this->clinicId();

        $data = $request->validate([
            'deal_id' => ['required', 'exists:deals,id'],
            'patient_id' => ['required', 'exists:users,id'],
        ]);

        $deal = Deal::where('clinic_id', $clinicId)->findOrFail($data['deal_id']);
        $patient = User::findOrFail($data['patient_id']);

        try {
            $patientDeal = $this->takeService->take($deal, $patient);
            session()->flash('success', __('deals.assigned', ['code' => $patientDeal->generated_code]));
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->back()->withInput();
        }

        return redirect()->route('manage-deals.index');
    }

    public function searchPatients(Request $request)
    {
        $term = trim((string) $request->get('q', ''));
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $patients = User::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('mobile_number', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'phone', 'mobile_number']);

        return response()->json($patients);
    }
}
