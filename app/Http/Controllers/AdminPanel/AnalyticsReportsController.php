<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\ReservationRate;
use App\Services\Analytics\AnalyticsExportService;
use App\Services\Analytics\AnalyticsFilterService;
use App\Services\Analytics\AnalyticsReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsReportsController extends Controller
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

        $this->middleware(function ($request, $next) {
            $account = Auth::user();
            if ($account && in_array((int) $account->app_type, [1, 7, 11], true)) {
                return redirect()->route('clinic-reports.appointments');
            }

            return $next($request);
        });
    }

    protected function boot(Request $request): array
    {
        $account = Auth::user();
        $this->filterService->authorizeAccount($account);
        $filters = $this->filterService->filtersFromRequest($request, $account);
        $options = $this->reports->filterOptions($filters);

        return [$account, $filters, $options];
    }

    public function index(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $widgets = $this->reports->dashboardWidgets($filters);
        $timeBased = $this->reports->timeBasedReport($filters, 'daily');
        $byStatus = $this->reports->reservationsByStatus($filters);

        return view('analytics.index', compact('filters', 'options', 'widgets', 'timeBased', 'byStatus') + [
            'section' => 'overview',
        ]);
    }

    public function reservations(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $stats = $this->reports->reservationStats($filters);
        $reservations = $this->reports->reservationsQuery($filters)
            ->latest('date')
            ->latest('id')
            ->paginate(30)
            ->appends($request->query());

        return view('analytics.reservations', compact('filters', 'options', 'stats', 'reservations') + [
            'section' => 'reservations',
            'reports' => $this->reports,
        ]);
    }

    public function byStatus(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $byStatus = $this->reports->reservationsByStatus($filters);

        return view('analytics.by_status', compact('filters', 'options', 'byStatus') + [
            'section' => 'by_status',
        ]);
    }

    public function timeBased(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $granularity = $request->input('granularity', 'daily');
        if (!in_array($granularity, ['daily', 'weekly', 'monthly', 'yearly'], true)) {
            $granularity = 'daily';
        }
        $timeBased = $this->reports->timeBasedReport($filters, $granularity);

        return view('analytics.time_based', compact('filters', 'options', 'timeBased', 'granularity') + [
            'section' => 'time_based',
        ]);
    }

    public function byDoctor(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $rows = $this->reports->reservationsByDoctor($filters);

        return view('analytics.by_doctor', compact('filters', 'options', 'rows') + [
            'section' => 'by_doctor',
        ]);
    }

    public function bySpecialty(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $rows = $this->reports->reservationsBySpecialty($filters);

        return view('analytics.by_specialty', compact('filters', 'options', 'rows') + [
            'section' => 'by_specialty',
        ]);
    }

    public function doctors(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $doctors = $this->reports->doctorsAnalytics($filters);
        $top = $this->reports->topDoctors($filters);

        return view('analytics.doctors', compact('filters', 'options', 'doctors', 'top') + [
            'section' => 'doctors',
        ]);
    }

    public function patients(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $summary = $this->reports->patientsDuringPeriod($filters);
        $perPatient = $this->reports->reservationsPerPatient($filters);
        $demographics = $this->reports->patientDemographics($filters);
        $topPatients = $this->reports->topPatients($filters);

        return view('analytics.patients', compact(
            'filters',
            'options',
            'summary',
            'perPatient',
            'demographics',
            'topPatients'
        ) + [
            'section' => 'patients',
        ]);
    }

    public function ratings(Request $request)
    {
        [, $filters, $options] = $this->boot($request);
        $ratings = $this->reports->ratingsAnalytics($filters);

        return view('analytics.ratings', compact('filters', 'options', 'ratings') + [
            'section' => 'ratings',
        ]);
    }

    public function resolveReview(Request $request, $id)
    {
        $account = Auth::user();
        $this->filterService->authorizeAccount($account);

        $rating = ReservationRate::findOrFail($id);

        if (!$this->filterService->isMainAdmin($account)) {
            $clinicId = $account->organizationClinicId();
            if ((int) $rating->clinic_id !== (int) $clinicId) {
                abort(403);
            }
        }

        $rating->follow_up_status = 'resolved';
        $rating->follow_up_notes = $request->input('follow_up_notes', $rating->follow_up_notes);
        $rating->resolved_at = now();
        $rating->resolved_by = $account->id;
        $rating->save();

        return back()->with('success', __('analytics.review_marked_resolved'));
    }

    public function export(Request $request, string $section)
    {
        [, $filters] = $this->boot($request);
        $format = $request->input('format', 'csv');

        switch ($section) {
            case 'reservations':
                $items = $this->reports->reservationsQuery($filters)->latest('date')->limit(5000)->get();
                $headers = [
                    __('analytics.reservation_number'),
                    __('analytics.patient_name'),
                    __('analytics.doctor_name'),
                    __('analytics.specialty'),
                    __('analytics.clinic_branch'),
                    __('analytics.reservation_date'),
                    __('analytics.reservation_time'),
                    __('analytics.reservation_status'),
                    __('analytics.visit_type'),
                    __('analytics.payment_status'),
                    __('analytics.booking_source'),
                ];
                $rows = $items->map(function ($item) {
                    $locale = app()->getLocale();
                    return [
                        $item->booking_number,
                        optional($item->user)->name,
                        optional($item->doctor)->name,
                        optional($item->specialty)->{'name_' . $locale},
                        optional($item->clinic)->name,
                        $item->date,
                        $item->appointment,
                        optional($item->reservation_status)->{'name_' . $locale}
                            ?? optional($item->reservation_status)->name_en,
                        $this->reports->visitTypeLabel($item),
                        $this->reports->paymentStatusLabel($item),
                        $this->reports->bookingSourceLabel($item),
                    ];
                });
                break;

            case 'by_doctor':
                $items = $this->reports->reservationsByDoctor($filters);
                $headers = [
                    __('analytics.doctor_name'),
                    __('analytics.specialty'),
                    __('analytics.total_reservations'),
                    __('analytics.completed_visits'),
                    __('analytics.cancelled_visits'),
                    __('analytics.avg_daily'),
                    __('analytics.revenue'),
                ];
                $rows = $items->map(fn ($r) => [
                    $r['doctor_name'], $r['specialty'], $r['total_reservations'],
                    $r['completed_visits'], $r['cancelled_visits'], $r['avg_daily'], $r['revenue'],
                ]);
                break;

            case 'by_specialty':
                $items = $this->reports->reservationsBySpecialty($filters);
                $headers = [
                    __('analytics.specialty'),
                    __('analytics.total_reservations'),
                    __('analytics.percentage'),
                    __('analytics.growth'),
                ];
                $rows = $items->map(fn ($r) => [
                    $r['specialty'], $r['total_reservations'], $r['percentage'], $r['growth'],
                ]);
                break;

            case 'doctors':
                $items = $this->reports->doctorsAnalytics($filters);
                $headers = [
                    __('analytics.doctor_name'),
                    __('analytics.total_reservations'),
                    __('analytics.completed_visits'),
                    __('analytics.cancelled_visits'),
                    __('analytics.no_show_count'),
                    __('analytics.satisfaction_score'),
                    __('analytics.avg_rating'),
                    __('analytics.avg_visit_duration'),
                ];
                $rows = $items->map(fn ($r) => [
                    $r['doctor_name'], $r['total_reservations'], $r['completed_visits'],
                    $r['cancelled_visits'], $r['no_show_count'], $r['satisfaction_score'],
                    $r['avg_rating'], $r['avg_visit_duration'],
                ]);
                break;

            case 'patients':
                $items = $this->reports->reservationsQuery($filters)
                    ->select('user_id')
                    ->selectRaw('COUNT(*) as total_reservations')
                    ->selectRaw('SUM(CASE WHEN status_id = 6 THEN 1 ELSE 0 END) as completed_visits')
                    ->selectRaw('SUM(CASE WHEN status_id IN (3,4,5) THEN 1 ELSE 0 END) as cancelled_visits')
                    ->groupBy('user_id')
                    ->orderByDesc('total_reservations')
                    ->limit(5000)
                    ->get();
                $headers = [
                    __('analytics.patient_name'),
                    __('analytics.total_reservations'),
                    __('analytics.completed_visits'),
                    __('analytics.cancelled_visits'),
                ];
                $rows = $items->map(function ($row) {
                    $user = \App\Models\User::find($row->user_id);
                    return [
                        $user->name ?? '-',
                        (int) $row->total_reservations,
                        (int) $row->completed_visits,
                        (int) $row->cancelled_visits,
                    ];
                });
                break;

            case 'ratings':
                $items = \App\Models\ReservationRate::query()
                    ->with(['users', 'doctors'])
                    ->when($filters['clinic_id'], fn ($q) => $q->where('clinic_id', $filters['clinic_id']))
                    ->whereBetween('created_at', [$filters['start'], $filters['end']])
                    ->latest()
                    ->limit(5000)
                    ->get();
                $headers = [
                    __('analytics.patient_name'),
                    __('analytics.doctor_name'),
                    __('analytics.rating'),
                    __('analytics.comment'),
                    __('analytics.date'),
                    __('analytics.follow_up_status'),
                ];
                $rows = $items->map(fn ($r) => [
                    optional($r->users)->name,
                    optional($r->doctors)->name,
                    $r->rate_value,
                    $r->comment,
                    optional($r->created_at)->format('Y-m-d'),
                    $r->follow_up_status ?? 'open',
                ]);
                break;

            default:
                abort(404);
        }

        return $this->exports->export($format, 'analytics-' . $section, $headers, $rows);
    }
}
