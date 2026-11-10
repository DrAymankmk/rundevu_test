<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DealRequest;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\Deal;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DealsController extends Controller
{
    public function index(Request $request)
    {
        Deal::deactivateExpired();

        $query = Deal::with(['clinic', 'specialty'])->orderByDesc('id');

        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%");
            });
        }

        $deals = $query->paginate(15)->withQueryString();
        $clinics = Clinic::where('app_type', 1)->where('status', 1)->orderBy('name')->get(['id', 'name', 'name_en']);

        return view('main_admin.deals.index', compact('deals', 'clinics'));
    }

    public function create()
    {
        $clinics = Clinic::where('app_type', 1)->where('status', 1)->orderBy('name')->get(['id', 'name', 'name_en']);

        return view('main_admin.deals.create', compact('clinics'));
    }

    public function store(DealRequest $request)
    {
        $this->assertClinicSpecialty($request->clinic_id, $request->specialty_id);

        Deal::create($request->validated());

        session()->flash('success', __('deals.created'));

        return redirect()->route('deals.index');
    }

    public function edit($id)
    {
        $deal = Deal::findOrFail($id);
        $clinics = Clinic::where('app_type', 1)->where('status', 1)->orderBy('name')->get(['id', 'name', 'name_en']);
        $specialties = $this->specialtiesForClinic($deal->clinic_id);

        return view('main_admin.deals.edit', compact('deal', 'clinics', 'specialties'));
    }

    public function update(DealRequest $request, $id)
    {
        $deal = Deal::findOrFail($id);
        $this->assertClinicSpecialty($request->clinic_id, $request->specialty_id);

        $data = $request->validated();
        if (!$request->hasFile('image')) {
            unset($data['image']);
        }

        $deal->update($data);

        session()->flash('success', __('deals.updated'));

        return redirect()->route('deals.index');
    }

    public function destroy($id)
    {
        Deal::findOrFail($id)->delete();
        session()->flash('success', __('deals.deleted'));

        return redirect()->back();
    }

    public function toggleStatus($id, $status)
    {
        $deal = Deal::findOrFail($id);
        $deal->status = $status === Deal::STATUS_ACTIVE ? Deal::STATUS_ACTIVE : Deal::STATUS_NOT_ACTIVE;
        $deal->save();

        session()->flash('success', __('deals.status_updated'));

        return redirect()->back();
    }

    public function specialties($clinicId)
    {
        return response()->json($this->specialtiesForClinic($clinicId));
    }

    protected function specialtiesForClinic($clinicId)
    {
        $ids = ClinicSpecialist::where('clinic_id', $clinicId)
            ->where('type', 1)
            ->where('status', 1)
            ->pluck('specialty_id');

        return Specialty::whereIn('id', $ids)
            ->where('status', 1)
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en']);
    }

    protected function assertClinicSpecialty($clinicId, $specialtyId): void
    {
        if (!$specialtyId) {
            return;
        }

        $valid = ClinicSpecialist::where('clinic_id', $clinicId)
            ->where('specialty_id', $specialtyId)
            ->where('type', 1)
            ->where('status', 1)
            ->exists();

        if (!$valid) {
            abort(422, __('deals.invalid_specialty'));
        }
    }
}
