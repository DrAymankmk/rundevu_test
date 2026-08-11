<?php

namespace App\Http\Controllers\Frontend;

use App\Events\SystemNotifications\ClinicRegistered;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\CmsPage;
use App\Models\Package;
use App\Models\SubscriptionsPackageClinic;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $cmsPage = CmsPage::query()
            ->where('slug', 'subscription')
            ->where('is_active', true)
            ->first();

        $cmsPageSections = collect();
        if ($cmsPage) {
            $cmsPageSections = $cmsPage->sections()
                ->active()
                ->ordered()
                ->with([
                    'translations',
                    'links' => static function ($q) {
                        $q->active()->ordered()->with('translations');
                    },
                    'items' => static function ($q) {
                        $q->active()->ordered()->with([
                            'translations',
                            'links' => static function ($lq) {
                                $lq->active()->ordered()->with('translations');
                            },
                        ]);
                    },
                ])
                ->get();
        }

        $packages = Package::query()
            ->where('status', 1)
            ->orderBy('price')
            ->get();

        $selectedPackageId = $request->filled('package') ? (int) $request->query('package') : null;
        $selectedPackage = $selectedPackageId
            ? $packages->firstWhere('id', $selectedPackageId)
            : null;

        return view('frontend.pages.subscription.index', compact(
            'cmsPage',
            'cmsPageSections',
            'packages',
            'selectedPackage',
            'selectedPackageId'
        ));
    }

    public function registerClinic(Request $request)
    {
        $attributes = [
            'clinic_name' => __('main.clinic_name'),
            'specialist' => __('main.select_specialists'),
            'specialist.*' => __('main.select_specialists'),
            'address' => __('main.address'),
            'phone_number' => __('main.phone_number'),
            'alternative_number' => __('main.alternative_number'),
            'admin_name' => __('main.name'),
            'admin_email' => __('main.email'),
            'admin_phone' => __('main.phone_number'),
            'clinic_license_number' => __('main.clinic_license_number'),
            'medical_commercial_license' => __('main.medical_commercial_license'),
            'email_address' => __('main.email_address'),
            'password' => __('main.password'),
            'package' => __('main.package'),
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'clinic_name' => 'required|string|max:255',
                'specialist' => 'required|array|min:1',
                'specialist.*' => [
                    'integer',
                    'distinct',
                    Rule::exists('specialties', 'id')->where(static function ($query) {
                        $query->whereNull('parent_id')->whereNull('deleted_at');
                    }),
                ],
                'address' => 'required|string|max:500',
                'phone_number' => 'required|string|max:50',
                'alternative_number' => 'nullable|string|max:50',
                'admin_name' => 'required|string|max:255',
                'admin_email' => 'required|email|max:255',
                'admin_phone' => 'required|string|max:50',
                'clinic_license_number' => 'required|string|max:100',
                'medical_commercial_license' => 'required|image|max:5120',
                'email_address' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('clinics', 'email')->where(static fn ($q) => $q->where('app_type', 1)),
                ],
                'password' => 'required|string|min:8|max:255',
                'package' => 'required|integer|exists:packages,id',
            ],
            [
                'specialist.required' => __('main.Please select at least one specialty'),
                'specialist.min' => __('main.Please select at least one specialty'),
            ],
            $attributes
        );

        if ($validator->fails()) {
            return $this->registrationFailureRedirect(
                $request,
                $validator->errors()->all(),
                __('main.registration_validation_failed')
            )->withErrors($validator);
        }

        $package = Package::query()
            ->where('id', $request->input('package'))
            ->where('status', 1)
            ->first();

        if (! $package) {
            return $this->registrationFailureRedirect(
                $request,
                [
                    __('main.package_not_found'),
                    __('main.package_not_found_detail', ['id' => $request->input('package')]),
                ],
                __('main.registration_failed')
            );
        }

        $duplicateCauses = $this->duplicateAccountCauses($request);
        if ($duplicateCauses !== []) {
            return $this->registrationFailureRedirect(
                $request,
                $duplicateCauses,
                __('main.registration_failed')
            );
        }

        DB::beginTransaction();

        try {
            $licenseFileName = $this->storeMedicalLicense($request->file('medical_commercial_license'));
            $packageEndDate = $this->resolvePackageEndDate($package);

            $clinic = Clinic::create([
                'name' => $request->input('clinic_name'),
                'email' => $request->input('email_address'),
                'phone' => $request->input('phone_number'),
                'alternative_phone' => $request->input('alternative_number'),
                'password' => Hash::make($request->input('password')),
                'address' => $request->input('address'),
                // 'communication_officer' => $request->input('admin_name'),
                // 'communication_officer_phone' => $request->input('admin_phone'),
                'license_number' => $request->input('clinic_license_number'),
                'medical_commercial_license' => $licenseFileName,
                'app_type' => 1,
                'status' => 0,
                'jwt_token' => Str::random(75),
                'package_end_date' => $packageEndDate,
                'date_created' => now()->toDateString(),
            ]);

            $clinic->update([
                'parent_id' => $clinic->id,
            ]);

            $specialistIds = collect($request->input('specialist', []))
                ->map(static fn ($id) => (int) $id)
                ->filter()
                ->unique()
                ->values();

            foreach ($specialistIds as $specialtyId) {
                ClinicSpecialist::updateOrCreate(
                    [
                        'clinic_id' => $clinic->id,
                        'specialty_id' => $specialtyId,
                        'type' => 1,
                    ],
                    [
                        'status' => 1,
                    ]
                );
            }

            SubscriptionsPackageClinic::create([
                'clinic_id' => $clinic->id,
                'package_id' => $package->id,
                'start_date' => now()->toDateString(),
                'end_date' => $packageEndDate->toDateString(),
                'status' => 1,
            ]);

            DB::commit();

            try {
                event(new ClinicRegistered($clinic, $package, [
                    'admin_name' => $request->input('admin_name'),
                    'admin_email' => $request->input('admin_email'),
                    'admin_phone' => $request->input('admin_phone'),
                ]));
            } catch (\Throwable $notificationError) {
                report($notificationError);
                Log::warning('Clinic registered but system notification failed.', [
                    'clinic_id' => $clinic->id,
                    'message' => $notificationError->getMessage(),
                ]);
            }

            return redirect()
                ->route('frontend.subscription')
                ->with('success', __('main.clinic_registered_successfully'));
        } catch (ValidationException $e) {
            DB::rollBack();

            return $this->registrationFailureRedirect(
                $request,
                collect($e->errors())->flatten()->all(),
                __('main.registration_validation_failed')
            )->withErrors($e->errors());
        } catch (QueryException $e) {
            DB::rollBack();
            report($e);

            return $this->registrationFailureRedirect(
                $request,
                $this->resolveExceptionCauses($e, true),
                __('main.registration_failed')
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return $this->registrationFailureRedirect(
                $request,
                $this->resolveExceptionCauses($e, false),
                __('main.registration_failed')
            );
        }
    }

    private function registrationFailureRedirect(Request $request, array $causes, ?string $title = null): RedirectResponse
    {
        $causes = array_values(array_filter(array_map('strval', $causes)));

        return redirect()
            ->route('frontend.subscription', array_filter([
                'package' => $request->input('package'),
            ]))
            ->withInput($request->except(['password', 'medical_commercial_license']))
            ->with('registration_error_title', $title ?? __('main.registration_failed'))
            ->with('registration_errors', $causes)
            ->with('error', $title ?? __('main.registration_failed'));
    }

    private function duplicateAccountCauses(Request $request): array
    {
        $causes = [];

        if (Clinic::query()->where('app_type', 1)->where('email', $request->input('email_address'))->exists()) {
            $causes[] = __('main.email_already_registered', ['email' => $request->input('email_address')]);
        }

        if (Clinic::query()->where('app_type', 1)->where('phone', $request->input('phone_number'))->exists()) {
            $causes[] = __('main.phone_already_registered', ['phone' => $request->input('phone_number')]);
        }

        return $causes;
    }

    private function resolveExceptionCauses(\Throwable $e, bool $isDatabase): array
    {
        if (config('app.debug')) {
            $causes = [
                __('main.registration_error_detail', ['message' => $e->getMessage()]),
            ];

            if ($e->getPrevious()) {
                $causes[] = __('main.registration_error_previous', ['message' => $e->getPrevious()->getMessage()]);
            }

            return $causes;
        }

        return [$isDatabase ? __('main.registration_database_error') : __('main.registration_failed')];
    }

    private function storeMedicalLicense($file): string
    {
        $directory = public_path('media/clinics/licenses');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $fileName);

        return $fileName;
    }

    private function resolvePackageEndDate(Package $package): Carbon
    {
        $endDate = Carbon::now()->addDays(max((int) $package->duration, 1));

        if ((int) $package->free_months > 0) {
            $endDate->addMonths((int) $package->free_months);
        }

        return $endDate;
    }
}
