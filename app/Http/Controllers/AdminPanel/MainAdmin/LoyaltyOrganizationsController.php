<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppType;
use App\Models\City;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\Package;
use App\Models\Specialty;
use App\Models\SubscriptionsPackageClinic;
use App\Services\ClinicModuleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LoyaltyOrganizationsController extends Controller
{
    private array $organizationAppTypes = [1, 4, 5, 7];

    public function index(Request $request, ClinicModuleService $modules)
    {
        $query = Clinic::with(['appType', 'specialties'])
            ->whereIn('app_type', $this->organizationAppTypes)
            ->withCount([
                'loyalty_coupons',
                'loyalty_redemptions',
            ])
            ->latest();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('app_type')) {
            $query->where('app_type', $request->app_type);
        }

        if ($request->filled('points_enabled')) {
            $query->where('points_enabled', (int) $request->points_enabled);
        }

        $organizations = $query->paginate(20)->appends($request->query());
        $appTypes = AppType::whereIn('id', $this->organizationAppTypes)->get();
        $moduleDefinitions = $modules->moduleDefinitions();
        $cities = City::where('status', 1)->orderBy('id')->get();
        $packages = Package::where('status', 1)->orderBy('id')->get();
        $specialties = Specialty::whereNull('parent_id')->where('status', 1)->orderByDesc('id')->get();
        $parentClinics = Clinic::where('app_type', 1)->where('status', 1)->orderBy('name')->get(['id', 'name']);

        return view('main_admin.loyalty.organizations', compact(
            'organizations',
            'appTypes',
            'moduleDefinitions',
            'cities',
            'packages',
            'specialties',
            'parentClinics'
        ));
    }

    public function store(Request $request, ClinicModuleService $modules)
    {
        $appType = (int) $request->input('app_type');
        $moduleKeys = $modules->allModuleKeys();

        $rules = $this->organizationValidationRules($appType, $moduleKeys);
        $validated = $request->validate($rules);

        if (Clinic::where('email', $validated['email'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['email' => trans('main.email_or_phone_exists')]);
        }

        if (! empty($validated['phone']) && Clinic::where('phone', $validated['phone'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['phone' => trans('main.email_or_phone_exists')]);
        }

        $selectedModules = array_values((array) $request->input('enabled_modules', []));
        $pointsEnabled = (bool) $validated['points_enabled'];

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'app_type' => $appType,
            'status' => (int) ($validated['status'] ?? 1),
            'jwt_token' => Str::random(75),
            'city_id' => $validated['city_id'] ?? null,
            'address' => $validated['address'] ?? null,
            'lat' => $validated['lat'] ?? 0,
            'lng' => $validated['lng'] ?? 0,
            'info' => $validated['info'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'medical_commercial_license' => $validated['medical_commercial_license'] ?? null,
            'alternative_phone' => $validated['alternative_phone'] ?? null,
            'communication_officer' => $validated['communication_officer'] ?? null,
            'communication_officer_phone' => $validated['communication_officer_phone'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'tiktok_url' => $validated['tiktok_url'] ?? null,
            'snapchat_url' => $validated['snapchat_url'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'points_category' => $validated['points_category'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $payload['image'] = $request->file('image');
        }

        if ($appType === 1 && ! empty($validated['package_id'])) {
            $package = Package::findOrFail($validated['package_id']);
            $payload['package_end_date'] = Carbon::now()->addDays($package->duration);
        }

        $organization = Clinic::create($payload);

        if ($appType === 7) {
            $organization->update(['parent_id' => (int) $validated['parent_id']]);
        } else {
            $organization->update(['parent_id' => $organization->id]);
        }

        if ($appType === 1 && ! empty($validated['package_id'])) {
            $package = Package::find($validated['package_id']);
            if ($package) {
                SubscriptionsPackageClinic::create([
                    'clinic_id' => $organization->id,
                    'package_id' => $package->id,
                    'start_date' => now(),
                    'end_date' => Carbon::now()->addDays($package->duration),
                    'status' => 1,
                ]);
            }
        }

        if ($appType === 1 && ! empty($validated['specialty_id'])) {
            foreach ((array) $validated['specialty_id'] as $specialtyId) {
                if ($specialtyId) {
                    ClinicSpecialist::create([
                        'clinic_id' => $organization->id,
                        'specialty_id' => $specialtyId,
                        'type' => 1,
                    ]);
                }
            }
        }

        $modules->syncOrganizationModules($organization->fresh(), $selectedModules, $pointsEnabled);

        session()->flash('success', trans('messages.updated'));

        return redirect()->route('loyalty-organizations');
    }

    public function update(Request $request, $id, ClinicModuleService $modules)
    {
        $moduleKeys = $modules->allModuleKeys();

        $request->validate([
            'points_enabled' => 'required|boolean',
            'points_category' => 'nullable|string|max:100',
            'enabled_modules' => 'required|array|min:1',
            'enabled_modules.*' => ['string', Rule::in($moduleKeys)],
        ]);

        $organization = Clinic::whereIn('app_type', $this->organizationAppTypes)->findOrFail($id);
        $pointsEnabled = (bool) $request->points_enabled;
        $selectedModules = array_values((array) $request->input('enabled_modules', []));

        $modules->syncOrganizationModules($organization, $selectedModules, $pointsEnabled);
        $organization->update(['points_category' => $request->points_category]);

        session()->flash('success', trans('messages.updated'));

        return redirect()->back();
    }

    public function updateOrganization(Request $request, $id)
    {
        $organization = Clinic::whereIn('app_type', $this->organizationAppTypes)->findOrFail($id);
        $appType = (int) $organization->app_type;

        $rules = $this->organizationValidationRules($appType, [], true, $organization->id);
        unset($rules['points_enabled'], $rules['points_category'], $rules['enabled_modules'], $rules['enabled_modules.*'], $rules['package_id']);

        $validated = $request->validate($rules);

        if (Clinic::where('email', $validated['email'])->where('id', '!=', $organization->id)->exists()) {
            return redirect()->back()->withInput()->withErrors(['email' => trans('main.email_or_phone_exists')]);
        }

        if (! empty($validated['phone']) && Clinic::where('phone', $validated['phone'])->where('id', '!=', $organization->id)->exists()) {
            return redirect()->back()->withInput()->withErrors(['phone' => trans('main.email_or_phone_exists')]);
        }

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => (int) ($validated['status'] ?? 1),
            'city_id' => $validated['city_id'] ?? null,
            'address' => $validated['address'] ?? null,
            'lat' => $validated['lat'] ?? $organization->lat,
            'lng' => $validated['lng'] ?? $organization->lng,
            'info' => $validated['info'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'medical_commercial_license' => $validated['medical_commercial_license'] ?? null,
            'alternative_phone' => $validated['alternative_phone'] ?? null,
            'communication_officer' => $validated['communication_officer'] ?? null,
            'communication_officer_phone' => $validated['communication_officer_phone'] ?? null,
            'facebook_url' => $validated['facebook_url'] ?? null,
            'instagram_url' => $validated['instagram_url'] ?? null,
            'tiktok_url' => $validated['tiktok_url'] ?? null,
            'snapchat_url' => $validated['snapchat_url'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('image')) {
            $payload['image'] = $request->file('image');
        }

        if ($appType === 7 && ! empty($validated['parent_id'])) {
            $payload['parent_id'] = (int) $validated['parent_id'];
        }

        $organization->update($payload);

        if ($appType === 1) {
            ClinicSpecialist::where('clinic_id', $organization->id)->where('type', 1)->delete();
            foreach ((array) ($validated['specialty_id'] ?? []) as $specialtyId) {
                if ($specialtyId) {
                    ClinicSpecialist::create([
                        'clinic_id' => $organization->id,
                        'specialty_id' => $specialtyId,
                        'type' => 1,
                    ]);
                }
            }
        }

        session()->flash('success', trans('messages.updated'));

        return redirect()->back();
    }

    public function toggleStatus($id, $status, ClinicModuleService $modules)
    {
        $status = (int) $status;
        if (! in_array($status, [0, 1], true)) {
            return response()->json(['status' => 0, 'message' => trans('messages.something_went_wrong')], 422);
        }

        $organization = Clinic::whereIn('app_type', $this->organizationAppTypes)->findOrFail($id);
        $modules->togglePointsEnabled($organization, (bool) $status);

        return response()->json([
            'status' => 1,
            'message' => trans('messages.updated'),
            'points_enabled' => $status,
        ]);
    }

    private function organizationValidationRules(int $appType, array $moduleKeys = [], bool $isUpdate = false, ?int $organizationId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clinics', 'email')->ignore($organizationId),
            ],
            'phone' => 'nullable|string|max:30',
            'password' => $isUpdate ? 'nullable|string|min:6' : 'required|string|min:6',
            'status' => 'required|boolean',
            'app_type' => ['required', 'integer', Rule::in($this->organizationAppTypes)],
            'points_enabled' => 'required|boolean',
            'points_category' => 'nullable|string|max:100',
            'enabled_modules' => 'required|array|min:1',
            'enabled_modules.*' => ['string', Rule::in($moduleKeys)],
            'city_id' => 'nullable|exists:cities,id',
            'address' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'info' => 'nullable|string',
            'specialization' => 'nullable|string|max:150',
            'license_number' => 'nullable|string|max:100',
            'medical_commercial_license' => 'nullable|string|max:255',
            'alternative_phone' => 'nullable|string|max:100',
            'communication_officer' => 'nullable|string|max:255',
            'communication_officer_phone' => 'nullable|string|max:20',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'snapchat_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|max:5120',
            'package_id' => 'nullable|exists:packages,id',
            'specialty_id' => 'nullable|array',
            'specialty_id.*' => 'exists:specialties,id',
            'parent_id' => 'nullable|exists:clinics,id',
        ];

        if ($appType === 1) {
            $rules['city_id'] = 'required|exists:cities,id';
            $rules['address'] = 'required|string|max:255';
            if (! $isUpdate) {
                $rules['package_id'] = 'required|exists:packages,id';
            }
            $rules['specialty_id'] = 'required|array|min:1';
        }

        if (in_array($appType, [4, 5], true)) {
            $rules['city_id'] = 'required|exists:cities,id';
            $rules['address'] = 'required|string|max:255';
        }

        if ($appType === 7) {
            $rules['parent_id'] = 'required|exists:clinics,id';
        }

        return $rules;
    }
}
