<?php

namespace App\Services\Analytics;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsFilterService
{
    public const STATUS_PENDING = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_CANCEL_DOCTOR = 3;
    public const STATUS_CANCEL_RECEPTION = 4;
    public const STATUS_CANCEL_PATIENT = 5;
    public const STATUS_COMPLETED = 6;

    public function authorizeAccount($account): void
    {
        if ((int) $account->app_type !== 6) {
            abort(403);
        }
    }

    public function authorizeClinicAccount($account): void
    {
        if (!in_array((int) $account->app_type, [1, 7, 11], true)) {
            abort(403);
        }
    }

    public function isMainAdmin($account): bool
    {
        return (int) $account->app_type === 6;
    }

    public function resolveClinicScope($account, ?int $requestedClinicId): ?int
    {
        if ($this->isMainAdmin($account)) {
            return $requestedClinicId;
        }

        return (int) $account->organizationClinicId();
    }

    /**
     * @return array{start: Carbon, end: Carbon, preset: string, previous_start: Carbon, previous_end: Carbon}
     */
    public function resolveDateRange(Request $request): array
    {
        $preset = $request->input('date_preset', 'this_month');
        $today = Carbon::today();

        switch ($preset) {
            case 'today':
            case 'daily':
                $start = $today->copy()->startOfDay();
                $end = $today->copy()->endOfDay();
                break;
            case 'yesterday':
                $start = $today->copy()->subDay()->startOfDay();
                $end = $today->copy()->subDay()->endOfDay();
                break;
            case 'this_week':
            case 'weekly':
                $start = $today->copy()->startOfWeek();
                $end = $today->copy()->endOfWeek();
                break;
            case 'custom':
                $start = $request->filled('date_from')
                    ? Carbon::parse($request->input('date_from'))->startOfDay()
                    : $today->copy()->startOfMonth();
                $end = $request->filled('date_to')
                    ? Carbon::parse($request->input('date_to'))->endOfDay()
                    : $today->copy()->endOfDay();
                break;
            case 'this_month':
            case 'monthly':
            default:
                $start = $today->copy()->startOfMonth();
                $end = $today->copy()->endOfMonth();
                if (!in_array($preset, ['this_month', 'monthly'], true)) {
                    $preset = 'monthly';
                }
                break;
        }

        $days = max($start->diffInDays($end) + 1, 1);
        $previousEnd = $start->copy()->subDay()->endOfDay();
        $previousStart = $previousEnd->copy()->subDays($days - 1)->startOfDay();

        return [
            'start' => $start,
            'end' => $end,
            'preset' => $preset,
            'previous_start' => $previousStart,
            'previous_end' => $previousEnd,
        ];
    }

    public function filtersFromRequest(Request $request, $account): array
    {
        $range = $this->resolveDateRange($request);
        $clinicId = $this->resolveClinicScope(
            $account,
            $request->filled('clinic_id') ? (int) $request->input('clinic_id') : null
        );

        return array_merge($range, [
            'clinic_id' => $clinicId,
            'doctor_id' => $request->filled('doctor_id') ? (int) $request->input('doctor_id') : null,
            'specialty_id' => $request->filled('specialty_id') ? (int) $request->input('specialty_id') : null,
            'status_id' => $request->filled('status_id') ? (int) $request->input('status_id') : null,
            'status_group' => $request->input('status_group'),
            'gender' => $request->filled('gender') ? (int) $request->input('gender') : null,
            'age_from' => $request->filled('age_from') ? (int) $request->input('age_from') : null,
            'age_to' => $request->filled('age_to') ? (int) $request->input('age_to') : null,
            'search' => trim((string) $request->input('search', '')),
            'payment_status' => $request->has('payment_status') && $request->input('payment_status') !== '' && $request->input('payment_status') !== null
                ? (int) $request->input('payment_status')
                : null,
            'booking_source' => $request->input('booking_source'),
            'patient_id' => $request->filled('patient_id') ? (int) $request->input('patient_id') : null,
            'is_main_admin' => $this->isMainAdmin($account),
        ]);
    }

    public function statusGroupIds(?string $group): ?array
    {
        $map = [
            'pending' => [self::STATUS_PENDING],
            'confirmed' => [self::STATUS_CONFIRMED],
            'completed' => [self::STATUS_COMPLETED],
            'cancelled_patient' => [self::STATUS_CANCEL_PATIENT],
            'cancelled_clinic' => [self::STATUS_CANCEL_DOCTOR, self::STATUS_CANCEL_RECEPTION],
            'cancelled' => [
                self::STATUS_CANCEL_DOCTOR,
                self::STATUS_CANCEL_RECEPTION,
                self::STATUS_CANCEL_PATIENT,
            ],
            'no_show' => [self::STATUS_PENDING, self::STATUS_CONFIRMED],
        ];

        if (!$group || !isset($map[$group])) {
            return null;
        }

        return $map[$group];
    }

    public function growthPercentage(float $current, float $previous): float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
