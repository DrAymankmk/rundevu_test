<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ReservationRate;
use App\Models\User;
use App\Services\Analytics\AnalyticsExportService;
use App\Services\Analytics\AnalyticsFilterService;
use App\Services\Analytics\AnalyticsReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicReportsController extends Controller
{
    protected AnalyticsFilterService $filterService;
    protected AnalyticsReportService $reports;
    protected AnalyticsExportService $exports;

    public function __construct(
        AnalyticsFilterService $filterService,
        AnalyticsReportService $reports,
        AnalyticsExportService $exports
    ) {
        $this->filterService = $filterService;
        $this->reports = $reports;
        $this->exports = $exports;
    }

    protected function boot(Request $request): array
    {
        $account = Auth::user();
        $this->filterService->authorizeClinicAccount($account);
        $filters = $this->filterService->filtersFromRequest($request, $account);
        $options = $this->reports->filterOptions($filters);

        return [$account, $filters, $options];
    }

    public function appointments(Request $request)
    {
        [, $filters, $options] = $this->boot($request);

        $reservations = $this->reports->reservationsQuery($filters)
            ->latest('date')
            ->latest('id')
            ->paginate(20)
            ->appends($request->query());

        return view('clinic_reports.appointments', compact('filters', 'options', 'reservations') + [
            'page' => 'appointments',
        ]);
    }

    public function doctors(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $counts = $this->reports->reservationsByDoctor($filters)->keyBy('doctor_id');

        $query = Clinic::query()
            ->where('app_type', 3)
            ->when($filters['clinic_id'], function ($q) use ($filters) {
                $q->where('parent_id', $filters['clinic_id']);
            })
            ->when($filters['doctor_id'], function ($q) use ($filters) {
                $q->where('id', $filters['doctor_id']);
            })
            ->when($filters['search'] !== '', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            })
            ->orderBy('name');

        $doctors = $query->paginate(20)->appends($request->query());
        $doctors->getCollection()->transform(function ($doctor) use ($counts) {
            $row = $counts->get($doctor->id, []);
            $doctor->total_reservations = (int) ($row['total_reservations'] ?? 0);
            $doctor->completed_visits = (int) ($row['completed_visits'] ?? 0);
            $doctor->cancelled_visits = (int) ($row['cancelled_visits'] ?? 0);
            $doctor->specialty_name = $row['specialty'] ?? '-';

            return $doctor;
        });

        $topBooked = $counts->sortByDesc('total_reservations')->take(10)->values();

        return view('clinic_reports.doctors', compact('filters', 'options', 'doctors', 'topBooked') + [
            'page' => 'doctors',
        ]);
    }

    public function patients(Request $request)
    {
        [, $filters, $options] = $this->boot($request);

        $paginator = $this->reports->reservationsQuery($filters)
            ->select('user_id')
            ->selectRaw('COUNT(*) as total_reservations')
            ->selectRaw('SUM(CASE WHEN status_id = 6 THEN 1 ELSE 0 END) as completed_visits')
            ->groupBy('user_id')
            ->orderByDesc('total_reservations')
            ->paginate(20)
            ->appends($request->query());

        $paginator->getCollection()->transform(function ($row) {
            $user = User::find($row->user_id);
            $row->patient_name = $user->name ?? '-';
            $row->phone = $user->phone ?? '-';
            $row->gender = $user->gender ?? null;

            return $row;
        });

        $demographics = $this->reports->patientDemographics($filters);
        $topPatients = $this->reports->topPatients($filters);

        return view('clinic_reports.patients', compact(
            'filters',
            'options',
            'paginator',
            'demographics',
            'topPatients'
        ) + [
            'page' => 'patients',
        ]);
    }

    public function reviews(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $ratings = $this->reports->ratingsAnalytics($filters);

        return view('clinic_reports.reviews', compact('filters', 'options', 'ratings') + [
            'page' => 'reviews',
        ]);
    }

    public function export(Request $request, string $section)
    {
        [, $filters] = $this->boot($request);
        $format = $request->input('format', 'csv');
        $locale = app()->getLocale();

        switch ($section) {
            case 'appointments':
                $items = $this->reports->reservationsQuery($filters)
                    ->latest('date')
                    ->latest('id')
                    ->limit(5000)
                    ->get();
                $headers = [
                    __('clinic_reports.id'),
                    __('clinic_reports.patient_name'),
                    __('clinic_reports.doctor_name'),
                    __('clinic_reports.specialty'),
                    __('clinic_reports.date'),
                    __('clinic_reports.time'),
                    __('clinic_reports.status'),
                ];
                $rows = $items->map(function ($item) use ($locale) {
                    return [
                        $item->id,
                        optional($item->user)->name,
                        optional($item->doctor)->name,
                        optional($item->specialty)->{'name_' . $locale},
                        $item->date,
                        $item->appointment,
                        optional($item->reservation_status)->{'name_' . $locale}
                            ?? optional($item->reservation_status)->name_en,
                    ];
                });
                $title = __('clinic_reports.appointments');
                break;

            case 'doctors':
                $counts = $this->reports->reservationsByDoctor($filters)->keyBy('doctor_id');
                $doctors = Clinic::query()
                    ->where('app_type', 3)
                    ->when($filters['clinic_id'], function ($q) use ($filters) {
                        $q->where('parent_id', $filters['clinic_id']);
                    })
                    ->when($filters['doctor_id'], function ($q) use ($filters) {
                        $q->where('id', $filters['doctor_id']);
                    })
                    ->when($filters['search'] !== '', function ($q) use ($filters) {
                        $q->where('name', 'like', '%' . $filters['search'] . '%');
                    })
                    ->orderBy('name')
                    ->limit(5000)
                    ->get();
                $headers = [
                    __('clinic_reports.id'),
                    __('clinic_reports.doctor_name'),
                    __('clinic_reports.specialty'),
                    __('clinic_reports.total_reservations'),
                    __('clinic_reports.completed_reservations'),
                    __('clinic_reports.cancelled_reservations'),
                ];
                $rows = $doctors->map(function ($doctor) use ($counts) {
                    $row = $counts->get($doctor->id, []);

                    return [
                        $doctor->id,
                        $doctor->name,
                        $row['specialty'] ?? '-',
                        (int) ($row['total_reservations'] ?? 0),
                        (int) ($row['completed_visits'] ?? 0),
                        (int) ($row['cancelled_visits'] ?? 0),
                    ];
                });
                $title = __('clinic_reports.doctors');
                break;

            case 'patients':
                $items = $this->reports->reservationsQuery($filters)
                    ->select('user_id')
                    ->selectRaw('COUNT(*) as total_reservations')
                    ->groupBy('user_id')
                    ->orderByDesc('total_reservations')
                    ->limit(5000)
                    ->get();
                $users = User::whereIn('id', $items->pluck('user_id')->filter())->get()->keyBy('id');
                $headers = [
                    __('clinic_reports.id'),
                    __('clinic_reports.patient_name'),
                    __('clinic_reports.phone'),
                    __('clinic_reports.total_reservations'),
                ];
                $rows = $items->map(function ($row) use ($users) {
                    $user = $users->get($row->user_id);

                    return [
                        $row->user_id,
                        $user->name ?? '-',
                        $user->phone ?? '-',
                        (int) $row->total_reservations,
                    ];
                });
                $title = __('clinic_reports.patients');
                break;

            case 'reviews':
                $items = ReservationRate::query()
                    ->with(['users', 'doctors'])
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
                    ->whereBetween('created_at', [$filters['start'], $filters['end']])
                    ->latest()
                    ->limit(5000)
                    ->get();
                $headers = [
                    __('clinic_reports.patient_name'),
                    __('clinic_reports.doctor_name'),
                    __('clinic_reports.rating'),
                    __('clinic_reports.comment'),
                    __('clinic_reports.date'),
                ];
                $rows = $items->map(function ($review) {
                    return [
                        optional($review->users)->name,
                        optional($review->doctors)->name,
                        $review->rate_value,
                        $review->comment,
                        optional($review->created_at)->format('Y-m-d'),
                    ];
                });
                $title = __('clinic_reports.reviews');
                break;

            default:
                abort(404);
        }

        return $this->exports->export($format, 'clinic-reports-' . $section, $headers, $rows, $title);
    }
}
