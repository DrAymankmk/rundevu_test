<?php

namespace App\Services\Analytics;

use App\Models\Clinic;
use App\Models\DoctorCondition;
use App\Models\ReservationRate;
use App\Models\Reservations;
use App\Models\Specialty;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsReportService
{
    protected AnalyticsFilterService $filters;

    public function __construct(AnalyticsFilterService $filters)
    {
        $this->filters = $filters;
    }

    public function filterOptions(array $filters): array
    {
        $clinicQuery = Clinic::query()
            ->whereIn('app_type', config('organization_fields.app_types', [1, 4, 5, 7]))
            ->orderBy('name');

        if (!$filters['is_main_admin'] && $filters['clinic_id']) {
            $clinicQuery->where('id', $filters['clinic_id']);
        }

        $clinics = $filters['is_main_admin']
            ? $clinicQuery->get(['id', 'name'])
            : collect();

        $doctorQuery = Clinic::query()
            ->where('app_type', 3)
            ->orderBy('name');

        if ($filters['clinic_id']) {
            $doctorQuery->where('parent_id', $filters['clinic_id']);
        }

        $specialties = Specialty::query()
            ->where('status', 1)
            ->orderBy('name_' . app()->getLocale())
            ->get(['id', 'name_ar', 'name_en', 'parent_id']);

        $statuses = Status::query()->orderBy('id')->get();

        $patients = collect();
        if (!empty($filters['clinic_id'])) {
            $patientIds = Reservations::query()
                ->where('clinic_id', $filters['clinic_id'])
                ->whereNotNull('user_id')
                ->distinct()
                ->limit(2000)
                ->pluck('user_id');
            $patients = User::query()
                ->whereIn('id', $patientIds)
                ->orderBy('name')
                ->get(['id', 'name', 'phone']);
        }

        return [
            'clinics' => $clinics,
            'doctors' => $doctorQuery->get(['id', 'name', 'parent_id']),
            'specialties' => $specialties,
            'statuses' => $statuses,
            'patients' => $patients,
        ];
    }

    public function reservationsQuery(array $filters, bool $applyDate = true): Builder
    {
        $query = Reservations::query()
            ->with(['user', 'doctor', 'clinic', 'specialty', 'reservation_status']);

        if ($applyDate) {
            $query->whereBetween('date', [
                $filters['start']->toDateString(),
                $filters['end']->toDateString(),
            ]);
        }

        if (!empty($filters['clinic_id'])) {
            $query->where('clinic_id', $filters['clinic_id']);
        }

        if (!empty($filters['doctor_id'])) {
            $query->where('doctor_id', $filters['doctor_id']);
        }

        if (!empty($filters['patient_id'])) {
            $query->where('user_id', $filters['patient_id']);
        }

        if (!empty($filters['specialty_id'])) {
            $query->where('sub_specialist_id', $filters['specialty_id']);
        }

        if (!empty($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        $groupIds = $this->filters->statusGroupIds($filters['status_group'] ?? null);
        if ($groupIds) {
            if (($filters['status_group'] ?? null) === 'no_show') {
                $query->whereIn('status_id', $groupIds)
                    ->whereDate('date', '<', Carbon::today()->toDateString());
            } else {
                $query->whereIn('status_id', $groupIds);
            }
        }

        if ($filters['payment_status'] !== null) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['booking_source'])) {
            if ($filters['booking_source'] === 'app') {
                $query->where('type', 1);
            } elseif ($filters['booking_source'] === 'reception') {
                $query->where(function ($q) {
                    $q->where('type', 2)->orWhereNotNull('reception_id');
                });
            } elseif ($filters['booking_source'] === 'website') {
                $query->where('type', 1)->whereNull('reception_id');
            }
        }

        if (!empty($filters['gender']) || $filters['age_from'] !== null || $filters['age_to'] !== null) {
            $query->whereHas('user', function ($q) use ($filters) {
                if (!empty($filters['gender'])) {
                    $q->where('gender', $filters['gender']);
                }
                if ($filters['age_from'] !== null || $filters['age_to'] !== null) {
                    $today = Carbon::today();
                    if ($filters['age_to'] !== null) {
                        $minDob = $today->copy()->subYears($filters['age_to'] + 1)->addDay();
                        $q->whereDate('dob', '>=', $minDob->toDateString());
                    }
                    if ($filters['age_from'] !== null) {
                        $maxDob = $today->copy()->subYears($filters['age_from']);
                        $q->whereDate('dob', '<=', $maxDob->toDateString());
                    }
                }
            });
        }

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    });
            });
        }

        return $query;
    }

    public function reservationStats(array $filters): array
    {
        $base = $this->reservationsQuery($filters);
        $total = (clone $base)->count();
        $confirmed = (clone $base)->where('status_id', AnalyticsFilterService::STATUS_CONFIRMED)->count();
        $completed = (clone $base)->where('status_id', AnalyticsFilterService::STATUS_COMPLETED)->count();
        $pending = (clone $base)->where('status_id', AnalyticsFilterService::STATUS_PENDING)->count();
        $cancelled = (clone $base)->whereIn('status_id', [
            AnalyticsFilterService::STATUS_CANCEL_DOCTOR,
            AnalyticsFilterService::STATUS_CANCEL_RECEPTION,
            AnalyticsFilterService::STATUS_CANCEL_PATIENT,
        ])->count();

        $prevFilters = $filters;
        $prevFilters['start'] = $filters['previous_start'];
        $prevFilters['end'] = $filters['previous_end'];
        $prevTotal = $this->reservationsQuery($prevFilters)->count();

        return [
            'total' => $total,
            'confirmed' => $confirmed,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'pending' => $pending,
            'growth' => $this->filters->growthPercentage($total, $prevTotal),
            'previous_total' => $prevTotal,
        ];
    }

    public function reservationsByStatus(array $filters): array
    {
        $total = max($this->reservationsQuery($filters)->count(), 1);
        $groups = [
            'confirmed' => AnalyticsFilterService::STATUS_CONFIRMED,
            'completed' => AnalyticsFilterService::STATUS_COMPLETED,
            'pending' => AnalyticsFilterService::STATUS_PENDING,
            'cancelled_patient' => AnalyticsFilterService::STATUS_CANCEL_PATIENT,
            'cancelled_clinic' => [
                AnalyticsFilterService::STATUS_CANCEL_DOCTOR,
                AnalyticsFilterService::STATUS_CANCEL_RECEPTION,
            ],
        ];

        $rows = [];
        $labels = [];
        $counts = [];

        foreach ($groups as $key => $status) {
            $q = $this->reservationsQuery($filters);
            if (is_array($status)) {
                $count = (clone $q)->whereIn('status_id', $status)->count();
            } else {
                $count = (clone $q)->where('status_id', $status)->count();
            }
            $rows[$key] = [
                'count' => $count,
                'percentage' => round(($count / $total) * 100, 1),
            ];
            $labels[] = __('analytics.status_' . $key);
            $counts[] = $count;
        }

        $noShow = $this->noShowCount($filters);
        $rows['no_show'] = [
            'count' => $noShow,
            'percentage' => round(($noShow / $total) * 100, 1),
        ];
        $labels[] = __('analytics.status_no_show');
        $counts[] = $noShow;

        // Trend: last 7 buckets within range by day for each major status
        $trend = $this->statusTrendSeries($filters);

        return [
            'rows' => $rows,
            'chart' => [
                'labels' => $labels,
                'series' => $counts,
            ],
            'trend' => $trend,
        ];
    }

    public function noShowCount(array $filters): int
    {
        return $this->reservationsQuery($filters)
            ->whereIn('status_id', [
                AnalyticsFilterService::STATUS_PENDING,
                AnalyticsFilterService::STATUS_CONFIRMED,
            ])
            ->whereDate('date', '<', Carbon::today()->toDateString())
            ->count();
    }

    protected function statusTrendSeries(array $filters): array
    {
        $start = $filters['start']->copy();
        $end = $filters['end']->copy();
        $days = min($start->diffInDays($end) + 1, 31);

        $labels = [];
        $completed = [];
        $cancelled = [];
        $pending = [];

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            if ($day->gt($end)) {
                break;
            }
            $labels[] = $day->format('Y-m-d');
            $dayFilters = $filters;
            $dayFilters['start'] = $day->copy()->startOfDay();
            $dayFilters['end'] = $day->copy()->endOfDay();
            $q = $this->reservationsQuery($dayFilters);
            $completed[] = (clone $q)->where('status_id', AnalyticsFilterService::STATUS_COMPLETED)->count();
            $cancelled[] = (clone $q)->whereIn('status_id', [
                AnalyticsFilterService::STATUS_CANCEL_DOCTOR,
                AnalyticsFilterService::STATUS_CANCEL_RECEPTION,
                AnalyticsFilterService::STATUS_CANCEL_PATIENT,
            ])->count();
            $pending[] = (clone $q)->where('status_id', AnalyticsFilterService::STATUS_PENDING)->count();
        }

        return compact('labels', 'completed', 'cancelled', 'pending');
    }

    public function timeBasedReport(array $filters, string $granularity = 'daily'): array
    {
        $query = $this->reservationsQuery($filters);

        switch ($granularity) {
            case 'weekly':
                $select = "YEARWEEK(date, 1)";
                $labelFormat = 'o-\WW';
                break;
            case 'monthly':
                $select = "DATE_FORMAT(date, '%Y-%m')";
                $labelFormat = 'Y-m';
                break;
            case 'yearly':
                $select = "YEAR(date)";
                $labelFormat = 'Y';
                break;
            case 'daily':
            default:
                $select = "DATE(date)";
                $labelFormat = 'Y-m-d';
                $granularity = 'daily';
                break;
        }

        $rows = (clone $query)
            ->selectRaw("$select as period_key")
            ->selectRaw('COUNT(*) as bookings')
            ->groupBy('period_key')
            ->orderBy('period_key')
            ->get();

        $labels = $rows->pluck('period_key')->map(function ($key) {
            return (string) $key;
        })->values()->all();
        $series = $rows->pluck('bookings')->map(fn ($v) => (int) $v)->values()->all();

        $current = array_sum($series);
        $prevFilters = $filters;
        $prevFilters['start'] = $filters['previous_start'];
        $prevFilters['end'] = $filters['previous_end'];
        $previous = $this->reservationsQuery($prevFilters)->count();

        return [
            'granularity' => $granularity,
            'labels' => $labels,
            'series' => $series,
            'bookings' => $current,
            'previous_bookings' => $previous,
            'growth' => $this->filters->growthPercentage($current, $previous),
            'rows' => $rows,
        ];
    }

    public function reservationsByDoctor(array $filters): Collection
    {
        $days = max($filters['start']->diffInDays($filters['end']) + 1, 1);

        return $this->reservationsQuery($filters)
            ->select('doctor_id')
            ->selectRaw('COUNT(*) as total_reservations')
            ->selectRaw('SUM(CASE WHEN status_id = 6 THEN 1 ELSE 0 END) as completed_visits')
            ->selectRaw('SUM(CASE WHEN status_id IN (3,4,5) THEN 1 ELSE 0 END) as cancelled_visits')
            ->selectRaw('COALESCE(SUM(CASE WHEN status_id = 6 THEN price ELSE 0 END), 0) as revenue')
            ->groupBy('doctor_id')
            ->orderByDesc('total_reservations')
            ->get()
            ->map(function ($row) use ($days) {
                $doctor = Clinic::find($row->doctor_id);
                $specialtyName = '-';
                if ($doctor) {
                    $spec = DB::table('clinic_specialists')
                        ->join('specialties', 'specialties.id', '=', 'clinic_specialists.specialty_id')
                        ->where('clinic_specialists.clinic_id', $doctor->id)
                        ->select('specialties.name_' . app()->getLocale() . ' as name')
                        ->first();
                    $specialtyName = $spec->name ?? '-';
                }

                return [
                    'doctor_id' => $row->doctor_id,
                    'doctor_name' => $doctor->name ?? '-',
                    'specialty' => $specialtyName,
                    'total_reservations' => (int) $row->total_reservations,
                    'completed_visits' => (int) $row->completed_visits,
                    'cancelled_visits' => (int) $row->cancelled_visits,
                    'avg_daily' => round(((int) $row->total_reservations) / $days, 2),
                    'revenue' => round((float) $row->revenue, 2),
                ];
            });
    }

    public function reservationsBySpecialty(array $filters): Collection
    {
        $total = max($this->reservationsQuery($filters)->count(), 1);
        $prevFilters = $filters;
        $prevFilters['start'] = $filters['previous_start'];
        $prevFilters['end'] = $filters['previous_end'];

        $prevBySpecialty = $this->reservationsQuery($prevFilters)
            ->select('sub_specialist_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('sub_specialist_id')
            ->pluck('total', 'sub_specialist_id');

        return $this->reservationsQuery($filters)
            ->select('sub_specialist_id')
            ->selectRaw('COUNT(*) as total_reservations')
            ->groupBy('sub_specialist_id')
            ->orderByDesc('total_reservations')
            ->get()
            ->map(function ($row) use ($total, $prevBySpecialty) {
                $specialty = Specialty::find($row->sub_specialist_id);
                $name = $specialty
                    ? ($specialty->{'name_' . app()->getLocale()} ?? $specialty->name_en)
                    : '-';
                $prev = (int) ($prevBySpecialty[$row->sub_specialist_id] ?? 0);
                $current = (int) $row->total_reservations;

                return [
                    'specialty_id' => $row->sub_specialist_id,
                    'specialty' => $name,
                    'total_reservations' => $current,
                    'percentage' => round(($current / $total) * 100, 1),
                    'growth' => $this->filters->growthPercentage($current, $prev),
                ];
            });
    }

    public function doctorsAnalytics(array $filters): Collection
    {
        $byDoctor = $this->reservationsByDoctor($filters)->keyBy('doctor_id');

        $ratings = ReservationRate::query()
            ->when($filters['clinic_id'], fn ($q) => $q->where('clinic_id', $filters['clinic_id']))
            ->when($filters['doctor_id'], fn ($q) => $q->where('doctor_id', $filters['doctor_id']))
            ->whereBetween('created_at', [$filters['start'], $filters['end']])
            ->select('doctor_id')
            ->selectRaw('AVG(rate_value) as avg_rating')
            ->selectRaw('COUNT(*) as reviews_count')
            ->groupBy('doctor_id')
            ->get()
            ->keyBy('doctor_id');

        $durations = DoctorCondition::query()
            ->when($filters['doctor_id'], fn ($q) => $q->where('doctor_id', $filters['doctor_id']))
            ->pluck('consultation_duration', 'doctor_id');

        $doctorIds = $byDoctor->keys()->merge($ratings->keys())->unique();

        return $doctorIds->map(function ($doctorId) use ($byDoctor, $ratings, $durations, $filters) {
            $stats = $byDoctor->get($doctorId, [
                'doctor_id' => $doctorId,
                'doctor_name' => Clinic::find($doctorId)->name ?? '-',
                'specialty' => '-',
                'total_reservations' => 0,
                'completed_visits' => 0,
                'cancelled_visits' => 0,
                'avg_daily' => 0,
                'revenue' => 0,
            ]);

            $noShow = $this->reservationsQuery($filters)
                ->where('doctor_id', $doctorId)
                ->whereIn('status_id', [
                    AnalyticsFilterService::STATUS_PENDING,
                    AnalyticsFilterService::STATUS_CONFIRMED,
                ])
                ->whereDate('date', '<', Carbon::today()->toDateString())
                ->count();

            $rating = $ratings->get($doctorId);
            $avgRating = $rating ? round((float) $rating->avg_rating, 2) : 0;
            $satisfaction = $avgRating > 0 ? round(($avgRating / 5) * 100, 1) : 0;

            return array_merge($stats, [
                'no_show_count' => $noShow,
                'avg_rating' => $avgRating,
                'satisfaction_score' => $satisfaction,
                'avg_visit_duration' => (int) ($durations[$doctorId] ?? 0),
                'reviews_count' => $rating ? (int) $rating->reviews_count : 0,
            ]);
        })->sortByDesc('total_reservations')->values();
    }

    public function topDoctors(array $filters): array
    {
        $all = $this->doctorsAnalytics($filters);

        return [
            'most_booked' => $all->sortByDesc('total_reservations')->take(10)->values(),
            'highest_rated' => $all->sortByDesc('avg_rating')->take(10)->values(),
            'most_completed' => $all->sortByDesc('completed_visits')->take(10)->values(),
        ];
    }

    public function patientsDuringPeriod(array $filters): array
    {
        $userIds = $this->reservationsQuery($filters)->distinct()->pluck('user_id');
        $totalPatients = $userIds->count();

        $newPatients = 0;
        $returning = 0;

        foreach ($userIds as $userId) {
            $firstReservation = Reservations::query()
                ->when($filters['clinic_id'], fn ($q) => $q->where('clinic_id', $filters['clinic_id']))
                ->where('user_id', $userId)
                ->orderBy('date')
                ->value('date');

            if ($firstReservation
                && Carbon::parse($firstReservation)->between($filters['start'], $filters['end'], true)
            ) {
                $newPatients++;
            } else {
                $returning++;
            }
        }

        return [
            'total_patients' => $totalPatients,
            'new_patients' => $newPatients,
            'returning_patients' => $returning,
        ];
    }

    public function reservationsPerPatient(array $filters)
    {
        $paginator = $this->reservationsQuery($filters)
            ->select('user_id')
            ->selectRaw('COUNT(*) as total_reservations')
            ->selectRaw('SUM(CASE WHEN status_id = 6 THEN 1 ELSE 0 END) as completed_visits')
            ->selectRaw('SUM(CASE WHEN status_id IN (3,4,5) THEN 1 ELSE 0 END) as cancelled_visits')
            ->selectRaw('COALESCE(SUM(CASE WHEN status_id = 6 THEN price ELSE 0 END), 0) as spending')
            ->groupBy('user_id')
            ->orderByDesc('total_reservations')
            ->paginate(30);

        $mapped = $paginator->getCollection()->map(function ($row) {
            $user = User::find($row->user_id);

            return [
                'user_id' => $row->user_id,
                'patient_name' => $user->name ?? '-',
                'phone' => $user->phone ?? '-',
                'total_reservations' => (int) $row->total_reservations,
                'completed_visits' => (int) $row->completed_visits,
                'cancelled_visits' => (int) $row->cancelled_visits,
                'spending' => round((float) $row->spending, 2),
            ];
        });

        $paginator->setCollection($mapped);

        return $paginator;
    }

    public function patientDemographics(array $filters): array
    {
        $userIds = $this->reservationsQuery($filters)->distinct()->pluck('user_id');
        $users = User::whereIn('id', $userIds)->get(['id', 'gender', 'dob']);

        $gender = [
            'male' => $users->where('gender', 1)->count(),
            'female' => $users->where('gender', 2)->count(),
            'other' => $users->where('gender', 3)->count(),
            'unknown' => $users->filter(fn ($u) => !in_array((int) $u->gender, [1, 2, 3], true))->count(),
        ];

        $ageGroups = [
            '0_18' => 0,
            '19_30' => 0,
            '31_45' => 0,
            '46_60' => 0,
            '60_plus' => 0,
            'unknown' => 0,
        ];

        foreach ($users as $user) {
            if (!$user->dob) {
                $ageGroups['unknown']++;
                continue;
            }
            $age = Carbon::parse($user->dob)->age;
            if ($age <= 18) {
                $ageGroups['0_18']++;
            } elseif ($age <= 30) {
                $ageGroups['19_30']++;
            } elseif ($age <= 45) {
                $ageGroups['31_45']++;
            } elseif ($age <= 60) {
                $ageGroups['46_60']++;
            } else {
                $ageGroups['60_plus']++;
            }
        }

        return compact('gender', 'ageGroups');
    }

    public function topPatients(array $filters): array
    {
        $base = $this->reservationsQuery($filters)
            ->select('user_id')
            ->selectRaw('COUNT(*) as total_reservations')
            ->selectRaw('COALESCE(SUM(CASE WHEN status_id = 6 THEN price ELSE 0 END), 0) as spending')
            ->groupBy('user_id');

        $frequent = (clone $base)->orderByDesc('total_reservations')->limit(10)->get()
            ->map(fn ($row) => [
                'patient_name' => optional(User::find($row->user_id))->name ?? '-',
                'total_reservations' => (int) $row->total_reservations,
                'spending' => round((float) $row->spending, 2),
            ]);

        $spending = (clone $base)->orderByDesc('spending')->limit(10)->get()
            ->map(fn ($row) => [
                'patient_name' => optional(User::find($row->user_id))->name ?? '-',
                'total_reservations' => (int) $row->total_reservations,
                'spending' => round((float) $row->spending, 2),
            ]);

        return compact('frequent', 'spending');
    }

    public function ratingsAnalytics(array $filters): array
    {
        $query = ReservationRate::query()
            ->with(['users', 'doctors', 'clinics'])
            ->when($filters['clinic_id'], fn ($q) => $q->where('clinic_id', $filters['clinic_id']))
            ->when($filters['doctor_id'], fn ($q) => $q->where('doctor_id', $filters['doctor_id']))
            ->when(!empty($filters['specialty_id']), function ($q) use ($filters) {
                $q->whereHas('reservations', function ($rq) use ($filters) {
                    $rq->where('sub_specialist_id', $filters['specialty_id']);
                });
            })
            ->when(($filters['search'] ?? '') !== '', function ($q) use ($filters) {
                $search = $filters['search'];
                $q->where(function ($inner) use ($search) {
                    $inner->where('comment', 'like', '%' . $search . '%')
                        ->orWhereHas('doctors', function ($dq) use ($search) {
                            $dq->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('users', function ($uq) use ($search) {
                            $uq->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->whereBetween('created_at', [$filters['start'], $filters['end']]);

        $total = (clone $query)->count();
        $avg = round((float) ((clone $query)->avg('rate_value') ?? 0), 2);
        $positive = (clone $query)->where('rate_value', '>=', 4)->count();
        $negative = (clone $query)->where('rate_value', '<=', 2)->count();

        $clinicAvg = $avg;
        $doctorAvg = $avg;

        $breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $breakdown[$i] = (clone $query)->where('rate_value', $i)->count();
        }

        $byMonth = (clone $query)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period")
            ->selectRaw('AVG(rate_value) as avg_rating')
            ->selectRaw('COUNT(*) as reviews')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $byDoctor = (clone $query)
            ->select('doctor_id')
            ->selectRaw('AVG(rate_value) as avg_rating')
            ->selectRaw('COUNT(*) as reviews')
            ->groupBy('doctor_id')
            ->orderByDesc('avg_rating')
            ->limit(15)
            ->get()
            ->map(function ($row) {
                return [
                    'doctor_name' => optional(Clinic::find($row->doctor_id))->name ?? '-',
                    'avg_rating' => round((float) $row->avg_rating, 2),
                    'reviews' => (int) $row->reviews,
                ];
            });

        $bySpecialty = DB::table('reservation_rates')
            ->join('reservations', 'reservations.id', '=', 'reservation_rates.reservation_id')
            ->leftJoin('specialties', 'specialties.id', '=', 'reservations.sub_specialist_id')
            ->when($filters['clinic_id'], fn ($q) => $q->where('reservation_rates.clinic_id', $filters['clinic_id']))
            ->whereBetween('reservation_rates.created_at', [$filters['start'], $filters['end']])
            ->selectRaw('reservations.sub_specialist_id')
            ->selectRaw('specialties.name_' . app()->getLocale() . ' as specialty_name')
            ->selectRaw('AVG(reservation_rates.rate_value) as avg_rating')
            ->selectRaw('COUNT(*) as reviews')
            ->groupBy('reservations.sub_specialist_id', 'specialty_name')
            ->orderByDesc('avg_rating')
            ->limit(15)
            ->get();

        $negativeReviews = (clone $query)
            ->where('rate_value', '<=', 2)
            ->latest()
            ->paginate(20);

        return [
            'clinic_avg_rating' => $clinicAvg,
            'doctor_avg_rating' => $doctorAvg,
            'total_reviews' => $total,
            'positive_reviews' => $positive,
            'negative_reviews_count' => $negative,
            'breakdown' => $breakdown,
            'by_month' => $byMonth,
            'by_doctor' => $byDoctor,
            'by_specialty' => $bySpecialty,
            'negative_reviews' => $negativeReviews,
        ];
    }

    public function dashboardWidgets(array $filters): array
    {
        $today = Carbon::today();
        $todayFilters = $filters;
        $todayFilters['start'] = $today->copy()->startOfDay();
        $todayFilters['end'] = $today->copy()->endOfDay();

        $todayReservations = $this->reservationsQuery($todayFilters)->count();
        $todayPatients = $this->reservationsQuery($todayFilters)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        $periodPatients = $this->patientsDuringPeriod($filters);
        $stats = $this->reservationStats($filters);
        $cancellationRate = $stats['total'] > 0
            ? round(($stats['cancelled'] / $stats['total']) * 100, 1)
            : 0;

        $activeDoctors = Clinic::query()
            ->where('app_type', 3)
            ->where('status', 1)
            ->when($filters['clinic_id'], fn ($q) => $q->where('parent_id', $filters['clinic_id']))
            ->count();

        $ratings = $this->ratingsAnalytics($filters);
        $topDoctor = $this->reservationsByDoctor($filters)->first();
        $topSpecialty = $this->reservationsBySpecialty($filters)->first();

        return [
            'reservations_today' => $todayReservations,
            'patients_today' => $todayPatients,
            'new_patients' => $periodPatients['new_patients'],
            'active_doctors' => $activeDoctors,
            'cancellation_rate' => $cancellationRate,
            'average_rating' => $ratings['clinic_avg_rating'],
            'most_booked_doctor' => $topDoctor['doctor_name'] ?? '-',
            'top_specialty' => $topSpecialty['specialty'] ?? '-',
            'stats' => $stats,
        ];
    }

    public function bookingSourceLabel($reservation): string
    {
        if ((int) $reservation->type === 2 || $reservation->reception_id) {
            return __('analytics.source_reception');
        }

        return __('analytics.source_app');
    }

    public function visitTypeLabel($reservation): string
    {
        return $reservation->follow_up
            ? __('analytics.visit_follow_up')
            : __('analytics.visit_new');
    }

    public function paymentStatusLabel($reservation): string
    {
        return (int) $reservation->payment_status === 1
            ? __('analytics.paid')
            : __('analytics.unpaid');
    }
}
