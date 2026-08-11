<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppType;
use App\Models\City;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['cities'] = City::select('id', 'name_' . app()->getLocale() . ' as name')->get();
        $data['app_types'] = AppType::whereIn('id', array(1))->select('id', 'name_' . app()->getLocale() . ' as name')->get();
        if ($request->app_type == 13) {
            $query = User::withCount('reservation_completed','reservation_not_completed')->latest();
        } else {
            $query = Clinic::withCount('clinic_points', 'doctors', 'reservations_clinics')
                ->latest();
            if ($request->app_type) {
                $query->where('app_type', $request->app_type);
            }
        }


//        if ($request->date_from && $request->date_to) {
//            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
//        } elseif ($request->date_from) {
//            $query->whereDate('created_at', '>=', $request->date_from);
//        } elseif ($request->date_to) {
//            $query->whereDate('created_at', '<=', $request->date_to);
//        }

        $reports = $query->get();
        $data['app_type'] = $request->app_type;

        return view('main_admin.reports.index', compact('reports', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
