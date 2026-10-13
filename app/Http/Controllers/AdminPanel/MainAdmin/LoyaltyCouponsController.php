<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Clinic;
use App\Models\LoyaltyRewardCoupon;
use App\Models\Notifications;
use App\Models\PointsExchange;
use App\Models\User;
use App\Services\FirebasePushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoyaltyCouponsController extends Controller
{
    private array $organizationAppTypes = [1, 4, 5, 7];

    public function index(Request $request)
    {
        $query = LoyaltyRewardCoupon::with('clinic')
            ->withCount(['redemptions as usage_count' => function ($q) {
                $q->whereIn('status', ['pending', 'otp_sent', 'used']);
            }])
            ->latest();

        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', (int) $request->clinic_id);
        }

        if ($request->filled('show_on_mobile_home')) {
            $query->where('show_on_mobile_home', (int) $request->show_on_mobile_home);
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        $coupons = $query->paginate(20)->appends($request->query());
        $clinics = $this->loyaltyClinics();
        $exchange = PointsExchange::latestActive();
        $branchesByClinic = $this->branchesGroupedByClinic($clinics->pluck('id')->all());

        return view('main_admin.loyalty.coupons', compact(
            'coupons',
            'clinics',
            'exchange',
            'branchesByClinic'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCouponRequest($request, true);

        LoyaltyRewardCoupon::create($validated + [
            'status' => 1,
        ]);

        session()->flash('success', trans('messages.Added'));

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $coupon = LoyaltyRewardCoupon::findOrFail($id);
        $validated = $this->validateCouponRequest($request, false, $coupon);

        $coupon->update($validated);

        session()->flash('success', trans('messages.updated'));

        return redirect()->back();
    }

    public function toggleStatus($id, $status)
    {
        $coupon = LoyaltyRewardCoupon::findOrFail($id);
        $coupon->update(['status' => (int) $status]);

        return response()->json(['message' => trans('messages.updated')]);
    }

    public function destroy($id)
    {
        $coupon = LoyaltyRewardCoupon::findOrFail($id);
        $coupon->delete();

        session()->flash('success', trans('messages.deleted'));

        return redirect()->back();
    }

    public function branches($clinicId)
    {
        $clinic = Clinic::whereIn('app_type', $this->organizationAppTypes)->findOrFail($clinicId);
        $locale = app()->getLocale();

        $branches = $this->branchOptionsForClinic($clinic)->map(function (Clinic $branch) use ($locale) {
            $cityName = $locale === 'ar'
                ? (optional($branch->city)->name_ar ?: optional($branch->city)->name_en)
                : (optional($branch->city)->name_en ?: optional($branch->city)->name_ar);

            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'city' => $cityName,
                'label' => $branch->name . ($cityName ? ' — ' . $cityName : ''),
            ];
        })->values();

        return response()->json(['branches' => $branches]);
    }

    public function patients(Request $request, $id)
    {
        $coupon = LoyaltyRewardCoupon::with('clinic')->findOrFail($id);
        $cityIds = $coupon->cityIds();
        $locale = app()->getLocale();
        $search = trim((string) $request->get('search', ''));

        $patients = collect();
        if ($cityIds !== []) {
            $patientsQuery = User::with('city')
                ->whereIn('city_id', $cityIds)
                ->where('status', 1);

            if ($search !== '') {
                $patientsQuery->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            }

            $patients = $patientsQuery
                ->orderBy('name')
                ->paginate(30)
                ->appends($request->only('search'));
        }

        $notificationsByUser = collect();
        if ($patients instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
            ? $patients->count() > 0
            : $patients->isNotEmpty()) {
            $notificationsByUser = Notifications::query()
                ->whereIn('user_id', $patients->pluck('id'))
                ->where(function ($query) use ($coupon) {
                    $query->where('url', $coupon->notificationUrl())
                        ->orWhere('flag', $coupon->id);
                })
                ->latest()
                ->get()
                ->groupBy('user_id');
        }

        $cityNames = City::whereIn('id', $cityIds ?: [-1])
            ->get()
            ->map(function (City $city) use ($locale) {
                return $locale === 'ar'
                    ? ($city->name_ar ?: $city->name_en)
                    : ($city->name_en ?: $city->name_ar);
            })
            ->filter()
            ->values()
            ->all();

        return view('main_admin.loyalty.partials.coupon-patients', compact(
            'coupon',
            'patients',
            'cityIds',
            'cityNames',
            'notificationsByUser',
            'locale',
            'search'
        ));
    }

    public function notifyPatient(Request $request, $id, $userId)
    {
        $coupon = LoyaltyRewardCoupon::findOrFail($id);
        $user = $this->patientsQueryForCoupon($coupon)->where('users.id', (int) $userId)->firstOrFail();

        try {
            $this->sendCouponNotification($coupon, $user);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage() ?: trans('main.loyalty_coupon_notification_failed'),
            ], 422);
        }

        return response()->json([
            'message' => trans('main.loyalty_coupon_notification_sent'),
            'user_id' => $user->id,
        ]);
    }

    public function notifyAll($id)
    {
        $coupon = LoyaltyRewardCoupon::findOrFail($id);
        $sent = 0;
        $failed = 0;

        $this->patientsQueryForCoupon($coupon)
            ->orderBy('id')
            ->chunkById(100, function ($users) use ($coupon, &$sent, &$failed) {
                foreach ($users as $user) {
                    try {
                        $this->sendCouponNotification($coupon, $user);
                        $sent++;
                    } catch (\Throwable $e) {
                        $failed++;
                    }
                }
            });

        if ($sent === 0 && $failed > 0) {
            return response()->json([
                'message' => trans('main.loyalty_coupon_notification_failed_all', ['failed' => $failed]),
                'count' => 0,
                'failed' => $failed,
            ], 422);
        }

        return response()->json([
            'message' => $failed > 0
                ? trans('main.loyalty_coupon_notification_sent_all_partial', [
                    'count' => $sent,
                    'failed' => $failed,
                ])
                : trans('main.loyalty_coupon_notification_sent_all', ['count' => $sent]),
            'count' => $sent,
            'failed' => $failed,
        ]);
    }

    public function notifyTest($id)
    {
        $coupon = LoyaltyRewardCoupon::findOrFail($id);
        $phone = (string) config('loyalty.test_notification_phone');

        $user = User::where('phone', $phone)
            ->orWhere('phone', 'like', '%' . ltrim($phone, '0'))
            ->first();

        if (! $user) {
            return response()->json([
                'message' => trans('main.loyalty_coupon_test_patient_not_found', ['phone' => $phone]),
            ], 404);
        }

        try {
            $notification = $this->sendCouponNotification($coupon, $user, true);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage() ?: trans('main.loyalty_coupon_notification_failed'),
            ], 422);
        }

        return response()->json([
            'message' => trans('main.loyalty_coupon_test_notification_sent', [
                'phone' => $user->phone,
                'name' => $user->name,
            ]),
            'notification' => [
                'id' => $notification->id,
                'title_ar' => $notification->title_ar,
                'title_en' => $notification->title_en,
                'message_ar' => $notification->message_ar,
                'message_en' => $notification->message_en,
                'created_at' => optional($notification->created_at)->format('Y-m-d H:i'),
            ],
        ]);
    }

    private function patientsQueryForCoupon(LoyaltyRewardCoupon $coupon)
    {
        $cityIds = $coupon->cityIds();

        return User::query()
            ->whereIn('city_id', $cityIds ?: [-1])
            ->where('status', 1);
    }

    private function sendCouponNotification(LoyaltyRewardCoupon $coupon, User $user, bool $isTest = false): Notifications
    {
        $titleAr = $isTest ? 'اختبار كوبون مكافأة' : 'كوبون مكافأة جديد';
        $titleEn = $isTest ? 'Reward coupon test' : 'New reward coupon';
        $serviceAr = $coupon->service_name_ar;
        $serviceEn = $coupon->service_name_en ?: $coupon->service_name_ar;
        $messageAr = trim($serviceAr . ($coupon->details_ar ? ' — ' . $coupon->details_ar : ''));
        $messageEn = trim($serviceEn . ($coupon->details_en ? ' — ' . $coupon->details_en : ''));

        if (empty($user->firebase_token)) {
            throw new \RuntimeException(trans('main.loyalty_coupon_notification_no_token', [
                'name' => $user->name,
            ]));
        }

        try {
            app(FirebasePushNotificationService::class)->sendToUser(
                (int) $user->id,
                $titleAr ?: $titleEn,
                $messageAr ?: $messageEn,
                [
                    'type' => '1',
                    'app_type' => '1',
                    'clinic_id' => (string) $coupon->clinic_id,
                    'url' => (string) $coupon->notificationUrl(),
                    'flag' => (string) $coupon->id,
                ]
            );
        } catch (\Throwable $e) {
            report($e);
            throw new \RuntimeException(trans('main.loyalty_coupon_notification_failed'), 0, $e);
        }

        // Skip model created events: Notifications::booted already sends Firebase on create.
        return Notifications::withoutEvents(function () use ($coupon, $user, $titleAr, $titleEn, $messageAr, $messageEn) {
            return Notifications::create([
                'clinic_id' => $coupon->clinic_id,
                'user_id' => $user->id,
                'receiver_id' => $user->id,
                'type' => 1,
                'app_type' => 1,
                'title_ar' => $titleAr,
                'title_en' => $titleEn,
                'message_ar' => $messageAr,
                'message_en' => $messageEn,
                'url' => $coupon->notificationUrl(),
                'coupon_status' => 1,
                'flag' => (int) $coupon->id,
            ]);
        });
    }

    private function validateCouponRequest(Request $request, bool $isCreate, ?LoyaltyRewardCoupon $coupon = null): array
    {
        $clinicId = (int) ($isCreate ? $request->clinic_id : ($request->clinic_id ?: optional($coupon)->clinic_id));
        $clinic = Clinic::whereIn('app_type', $this->organizationAppTypes)->find($clinicId);
        $allowedBranchIds = $clinic
            ? $this->branchOptionsForClinic($clinic)->pluck('id')->all()
            : [];

        $allowedClinicIds = $this->loyaltyClinics()->pluck('id')->all();

        $validated = $request->validate([
            'clinic_id' => [
                $isCreate ? 'required' : 'nullable',
                'integer',
                Rule::in($allowedClinicIds ?: [-1]),
            ],
            'service_name_ar' => 'required|string|max:255',
            'service_name_en' => 'nullable|string|max:255',
            'details_ar' => 'nullable|string',
            'details_en' => 'nullable|string',
            'price_before_discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                $request->discount_type === 'percentage' ? 'max:100' : 'max:999999',
            ],
            'usage_limit' => 'nullable|integer|min:0',
            'expires_at' => $isCreate ? 'required|date|after_or_equal:today' : 'required|date',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => ['integer', Rule::in($allowedBranchIds ?: [-1])],
            'show_on_mobile_home' => 'nullable|boolean',
        ]);

        if (! $clinic) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'clinic_id' => [trans('validation.exists', ['attribute' => 'clinic_id'])],
            ]);
        }

        $priceBefore = (float) $validated['price_before_discount'];
        $discountValue = (float) $validated['discount_value'];

        if ($validated['discount_type'] === 'fixed' && $discountValue > $priceBefore) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'discount_value' => [trans('main.loyalty_discount_exceeds_price')],
            ]);
        }

        $validated['clinic_id'] = (int) $clinic->id;
        $validated['usage_limit'] = (int) ($validated['usage_limit'] ?? 0);
        $validated['price_after_discount'] = LoyaltyRewardCoupon::calculatePriceAfterDiscount(
            $priceBefore,
            $validated['discount_type'],
            $discountValue
        );
        $validated['branch_ids'] = array_values(array_unique(array_map(
            'intval',
            $validated['branch_ids'] ?? []
        )));
        $validated['show_on_mobile_home'] = $request->boolean('show_on_mobile_home');

        return $validated;
    }

    private function loyaltyClinics()
    {
        return Clinic::with('city')
            ->whereIn('app_type', $this->organizationAppTypes)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'app_type', 'parent_id', 'city_id', 'points_enabled']);
    }

    private function branchOptionsForClinic(Clinic $clinic)
    {
        if ((int) $clinic->app_type === 7) {
            return Clinic::with('city')
                ->where('id', $clinic->id)
                ->get(['id', 'name', 'city_id', 'parent_id', 'app_type']);
        }

        return Clinic::with('city')
            ->where('status', 1)
            ->where('app_type', 7)
            ->where('parent_id', $clinic->id)
            ->orderBy('name')
            ->get(['id', 'name', 'city_id', 'parent_id', 'app_type']);
    }

    private function branchesGroupedByClinic(array $clinicIds): array
    {
        $grouped = [];
        $locale = app()->getLocale();

        foreach (Clinic::with('city')->whereIn('id', $clinicIds)->get() as $clinic) {
            $grouped[$clinic->id] = $this->branchOptionsForClinic($clinic)->map(function (Clinic $branch) use ($locale) {
                $cityName = $locale === 'ar'
                    ? (optional($branch->city)->name_ar ?: optional($branch->city)->name_en)
                    : (optional($branch->city)->name_en ?: optional($branch->city)->name_ar);

                return [
                    'id' => $branch->id,
                    'label' => $branch->name . ($cityName ? ' — ' . $cityName : ''),
                ];
            })->values()->all();
        }

        return $grouped;
    }
}
