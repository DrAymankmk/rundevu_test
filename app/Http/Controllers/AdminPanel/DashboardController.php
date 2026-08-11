<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\ClinicModuleService;
use App\Models\AppType;
use App\Models\Clinic;
use App\Models\ClinicPoint;
use App\Models\ClinicRating;
use App\Models\ComplaintBox;
use App\Models\PatientService;
use App\Models\ReservationDrug;
use App\Models\Reservations;
use App\Models\Specialty;
use App\Models\Status;
use App\Models\SubscriptionsPackageUser;
use App\Models\User;
use App\Models\ReservationChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\ReservationChatMessageEvent;

class DashboardController extends Controller
{
    function Dashboard(Request $request)
    {
        if (auth()->user()->app_type == 2 || auth()->user()->app_type == 6) {
            $auth_app = Auth::user()->id;
            if (auth()->user()->app_type == 2 ) {
                $data['receptions'] = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 2)->pluck('id');
                $data['doctors'] = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 3)->get();
                $reception_ids = Clinic::where('parent_id', auth()->user()->parent_id)->where('app_type', 2)->pluck('id');
                $data['patients'] = User::orderBy('id', 'desc')->get();

//        $doctors_reservations = Reservations::whereIn('reception_id', $data['receptions'])->OrwhereIn('doctor_id', $data['doctors']->pluck('id') ?? null)->orderBy('id', 'desc');

                $doctors_reservations = Reservations::latest()->whereIn('doctor_id', $data['doctors']->pluck('id') ?? null);
                $reception_reservations = Reservations::latest()->whereIn('reception_id', $data['receptions']);
                if ($request->date_from) {
                    $doctors_reservations->whereDate('date', '>=', $request->date_from)
                        ->whereDate('date', '<=', $request->date_to);
                    $reception_reservations->whereDate('date', '>=', $request->date_from)
                        ->whereDate('date', '<=', $request->date_to);
                } else {
                    $doctors_reservations->whereDate('date', date('Y-m-d'));
                    $reception_reservations->whereDate('date', date('Y-m-d'));
                }
                // Combine the reservations of doctors and receptions into a single query
                $query = $doctors_reservations->union($reception_reservations);


                if ($request->has('search') && !empty($request->search)) {
                    $search = $request->search;
                    $query->whereHas('user', function($q) use ($search) {
                        $q->where('phone', 'LIKE', "%{$search}%")
                            ->orWhere('name', 'LIKE', "%{$search}%");
                    });
                }

                if ($request->doctor_id) {
                    $query->where('doctor_id', $request->doctor_id);
                }
                if ($request->patient_id) {
                    $query->where('user_id', $request->patient_id);
                }

                if ($request->date_from) {
                    $query->whereDate('date', '>=', $request->date_from)
                        ->whereDate('date', '<=', $request->date_to);
                } else {
                    $query->whereDate('date', date('Y-m-d'));
                }
                $data['specialists'] = Specialty::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
                $data['reservations'] = $query->latest()->get();

                return view('home.reception', compact('data'));

            } elseif (auth()->user()->app_type == 6) {
                $data = [];

                /* =========================
                    COUNTERS
                ========================== */
                $data['total_clinics'] = Clinic::where('app_type', 1)->count();
                $data['total_doctors'] = Clinic::where('app_type', 3)->count();
                $data['total_users'] = User::count();
                $data['total_appointments'] = Reservations::count();

                // مستخدمين مشتركين (unique users)
                $data['total_subscribed_users'] = SubscriptionsPackageUser::distinct('user_id')->count('user_id');

                /* =========================
                    TODAY APPOINTMENTS
                ========================== */
                $data['appointments'] = Reservations::with(['doctor','user','clinic','reservation_status'])
                    ->whereDate('date', Carbon::today())
                    ->latest()
                    ->limit(5)
                    ->get();

                /* =========================
                    STATUSES
                ========================== */
                $data['statuses'] = Status::withCount('reservations')->get();

                /* =========================
                    TOP DOCTORS (BY RESERVATIONS)
                ========================== */
                $data['top_doctors'] = Clinic::where('app_type', 3)
                    ->withCount('reservations')
                    ->orderByDesc('reservations_count')
                    ->take(5)
                    ->get();

                /* =========================
                    COMPLAINTS BOX
                ========================== */
                $data['complaints_box'] = ComplaintBox::whereNotNull('clinic_id')
                    ->latest()
                    ->limit(5)
                    ->get();

                /* =========================
                    NOT ACTIVATED CLINICS + RATING
                ========================== */
                $data['clinics_not_activated'] = Clinic::where('status', 0)
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->each(function ($clinic) {
                        $clinic->rating = ClinicRating::where('clinic_id', $clinic->id)
                            ->whereNotNull('comment')
                            ->avg('rate_value');
                    });

                /* =========================
                    APP TYPES (GROWTH % LAST 7 DAYS)
                ========================== */
                $sevenDaysAgo = Carbon::now()->subDays(7);

                $data['app_types'] = AppType::withCount('accounts')
                    ->whereIn('id', [1, 2, 3])
                    ->get()
                    ->map(function ($type) use ($sevenDaysAgo) {

                        $newAccounts = $type->accounts()
                            ->where('created_at', '>=', $sevenDaysAgo)
                            ->count();

                        $oldAccounts = $type->accounts()
                            ->where('created_at', '<', $sevenDaysAgo)
                            ->count();

                        $type->growth_percent = $oldAccounts > 0
                            ? round(($newAccounts / $oldAccounts) * 100, 1)
                            : ($newAccounts > 0 ? 100 : 0);

                        $type->new_accounts = $newAccounts;

                        return $type;
                    });

                /* =========================
                    TOP RESERVATIONS (BY TOTAL PAID)
                ========================== */
                $data['top_reservations'] = Reservations::with('user')
                    ->select(
                        'user_id',
                        DB::raw('SUM(price) as total_paid'),
                        DB::raw('COUNT(id) as total_appointments')
                    )
                    ->groupBy('user_id')
                    ->orderByDesc('total_paid')
                    ->take(5)
                    ->get();

                /* =========================
                    EXPIRING CLINICS
                ========================== */
                $now = Carbon::now();

                // 🔴 خلال أسبوع
                $data['expiring_clinics_week'] = Clinic::whereNotNull('package_end_date')
                    ->whereBetween('package_end_date', [$now, $now->copy()->addWeek()])
                    ->orderBy('package_end_date')
                    ->get();

                // 🟠 خلال شهر
                $data['expiring_clinics_month'] = Clinic::whereNotNull('package_end_date')
                    ->whereBetween('package_end_date', [$now, $now->copy()->addMonth()])
                    ->orderBy('package_end_date')
                    ->get();

                return view('home.main_dashboard', compact('data'));
            }  else {
                $data['lang'] = app()->getLocale() ?? 'en';
                $data['points'] = ClinicPoint::where('clinic_id', $auth_app)->sum('point');
                if (auth()->user()->app_type == 5) {
                    $blade = 'pharmacy';
                    $reservation_ids = Reservations::where('clinic_id', auth()->user()->parent_id)->pluck('id');
                    $data['diagnosis_display'] = ReservationDrug::whereIn('reservation_id', $reservation_ids)->where('created_at', '>', Carbon::now()->subDays(2))->get();
                    $data['diagnosis_count'] = ReservationDrug::whereIn('reservation_id', $reservation_ids)->where('status', 0)->count();
                    $data['medicines_dispensed_count'] = ReservationDrug::whereIn('reservation_id', $reservation_ids)->where('status', 1)->count();
                    $data['medicines_dispensed'] = ReservationDrug::whereIn('reservation_id', $reservation_ids)->where('created_at', '>', Carbon::now()->subDays(2))->where('status', 1)->get();
                    $data['treatment_requests'] = ReservationDrug::whereIn('reservation_id', $reservation_ids)->where('created_at', '>', Carbon::now()->subDays(7))->select('id', DB::raw('count(*) as count'), DB::raw('DATE(created_at) AS date'))->groupBy(DB::raw('DATE(created_at)'))->get();
                } else {

                    $clinic_doctor_ids = Clinic::where(['app_type' => 3, 'status' => 1])->pluck('id');
                    $blade = 'lab';
                    if (auth()->user()->app_type == 25) {
                        $type = 1;
                    } else {
                        $type = 2;
                    }
                    $patient_service = PatientService::where('type', $type)->whereIn('doctor_id', $clinic_doctor_ids);
                    $data['total_number_invoices'] = $patient_service->count();
                    if ($request->date_from) {
                        $carbonDate = Carbon::createFromFormat('Y-m-d', $request->date_from);
                        $date_from = $carbonDate->format('Y-m-d');
                        $carbonDateTo = Carbon::createFromFormat('Y-m-d', $request->date_to);
                        $date_to = $carbonDateTo->format('Y-m-d');

//                        $patient_service->whereBetween('created_at', [$date_from, $date_to]);
                        $patient_service = $patient_service->whereDate('created_at', '>=', $date_from)
                            ->whereDate('created_at', '<=', $date_to);
                    } else {
                        $patient_service->whereDate('created_at', Carbon::now()->format('Y-m-d'));
                    }

                    if ($request->name) {
                        $patient_service->whereHas('user', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->name . '%')
                                ->orWhere('file_number', 'like', '%' . $request->name . '%')
                                ->orWhere('ID_Number', 'like', '%' . $request->name . '%');
                        });
                    }

                    $data['analysis'] = $patient_service->groupBy('user_id')->get();
                    $data['total_number_invoices_today'] = $patient_service->whereDate('created_at', Carbon::now()->format('Y-m-d'))->count();
                    $data['total_number_invoices_unfinished'] = $patient_service->where('status', 0)->count();
                    $data['total_number_invoices_finished'] = $patient_service->where('status', 1)->count();
                }
            }
            return view('home.' . $blade, compact('data'));
        } else {
            $modules = app(ClinicModuleService::class);
            if (in_array((int) auth()->user()->app_type, [1, 7, 11], true)
                && ! $modules->hasModule(auth()->user(), 'clinic_admin')) {
                return redirect()->route($modules->loginRedirectRoute(auth()->user()));
            }

            $auth_app = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
            $data['doctors'] = Clinic::where('parent_id', $auth_app)->where('app_type',1)->count();
            $data['receptionists'] = Clinic::where('parent_id', $auth_app)->where('app_type', 2)->count();
            $data['branches'] = Clinic::where('parent_id', $auth_app)->where('app_type', 7)->count();
            $data['specialties_count'] = \App\Models\ClinicSpecialist::where('clinic_id', $auth_app)->where('type', 1)->count();
            $data['offers_count'] = \App\Models\ClinicOffer::where('clinic_id', $auth_app)->count();
            $data['reservations_count'] = Reservations::where('clinic_id', $auth_app)->count();
            $data['today_reservations_count'] = Reservations::where('clinic_id', $auth_app)->whereDate('date', date('Y-m-d'))->count();
            $data['week_reservations_count'] = Reservations::where('clinic_id', $auth_app)
                ->whereBetween('date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])
                ->count();
            $data['pending_reservations_count'] = Reservations::where('clinic_id', $auth_app)
                ->where('status_id', '!=', 6)
                ->count();
            $data['completed_reservations_count'] = Reservations::where('clinic_id', $auth_app)
                ->where('status_id', 6)
                ->count();
            $data['patients_count'] = Reservations::where('clinic_id', $auth_app)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id');
            $data['patient_services_count'] = PatientService::where('clinic_id', $auth_app)->count();
            $data['active_doctors_count'] = Clinic::where('parent_id', $auth_app)
                ->where('app_type', 3)
                ->where('status', 1)
                ->count();
            $data['complaints_count'] = ComplaintBox::where('clinic_id', $auth_app)->count();
            $data['patients_waiting'] = Reservations::where(['doctor_id' => Auth::user()->id, 'date' => date('Y-m-d')])->orderBy('id', 'asc')->paginate(10);
            $data['complains_charts'] = ComplaintBox::where('clinic_id', $auth_app)->where('created_at', '>', Carbon::now()->subDays(7))->select('id', DB::raw('count(*) as count'), DB::raw('DATE(created_at) AS date'))->groupBy(DB::raw('DATE(created_at)'))->get();
            $data['reservations_charts'] = Reservations::where('clinic_id', $auth_app)->where('created_at', '>', Carbon::now()->subDays(7))->select('id', DB::raw('count(*) as count'), DB::raw('DATE(created_at) AS date'))->groupBy(DB::raw('DATE(created_at)'))->get();

            return view('home.index', compact('data'));
        }


    }
}
