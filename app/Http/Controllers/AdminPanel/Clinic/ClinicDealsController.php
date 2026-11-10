<?php

namespace App\Http\Controllers\AdminPanel\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;

class ClinicDealsController extends Controller
{
    protected function clinicId(): int
    {
        return (int) auth()->user()->organizationClinicId();
    }

    public function index(Request $request)
    {
        Deal::deactivateExpired();

        $deals = Deal::with('specialty')
            ->where('clinic_id', $this->clinicId())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('clinic.deals.index', compact('deals'));
    }

    public function show($id)
    {
        $deal = Deal::with(['specialty', 'clinic'])
            ->where('clinic_id', $this->clinicId())
            ->findOrFail($id);

        return view('clinic.deals.show', compact('deal'));
    }
}
