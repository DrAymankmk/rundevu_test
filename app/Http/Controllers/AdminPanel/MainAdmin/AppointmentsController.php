<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Reservations;
use App\Models\Specialty;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    function appointments_list(Request $request)
    {
        $query = Reservations::with(['doctor.specialties.specialties', 'user', 'reservation_status']);

        // فلترة بالدكتور
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // فلترة بالتاريخ
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // فلترة بالستاتس
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // ✅ الترتيب
        if ($request->get('sort') === 'oldest') {
            $query->oldest(); // orderBy('created_at', 'asc')
        } else {
            $query->latest(); // orderBy('created_at', 'desc') = Recent
        }

        $reservations = $query->latest()->paginate(15)->appends($request->query());

        // علشان نرجع الدكاترة والستاتس للفلاتر
        $doctors = Clinic::where('app_type',3)->latest()->get();
        $statuses = Status::all();

        return view('main_admin.appointments', [
            'data' => ['reservations' => $reservations],
            'doctors' => $doctors,
            'statuses' => $statuses,
        ]);
    }

}
