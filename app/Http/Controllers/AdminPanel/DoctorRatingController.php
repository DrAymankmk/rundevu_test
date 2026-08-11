<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\ReservationRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorRatingController extends Controller
{
    //  doctors rating
    public function index(Request $request)
    {
        $clinic_id = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        $query = ReservationRate::with(['doctors','users','reservations'])
            ->where('clinic_id', $clinic_id);

        if ($request->search) {
            $query->whereHas('doctors', function($q) use ($request){
                $q->where('name','like','%'.$request->search.'%');
            });
        }

        $summaryQuery = clone $query;
        $summaryTotal = (clone $summaryQuery)->count();
        $summaryPositive = (clone $summaryQuery)->where('rate_value', '>=', 4)->count();

        $summary = [
            'total' => $summaryTotal,
            'average' => (clone $summaryQuery)->avg('rate_value') ?? 0,
            'positive' => $summaryPositive,
            'positive_percentage' => $summaryTotal > 0 ? round(($summaryPositive / $summaryTotal) * 100, 1) : 0,
            'with_comments' => (clone $summaryQuery)
                ->whereNotNull('comment')
                ->where('comment', '!=', '')
                ->count(),
        ];

        if ($request->order == 'top') {
            $query->orderByDesc('rate_value')->latest();
        } elseif ($request->order == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $ratings = $query->paginate(10)->withQueryString();

        return view('doctors.ratings', compact('ratings', 'summary'));
    }


}
