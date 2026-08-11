<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class CityControllerController extends Controller
{
    // get cities
    function index()
    {
        $data['cities'] = City::withCount('clinics')->latest()->get();
        return view('main_admin.cities', compact('data'));
    }


    // add city
    public function add_city(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $data['country_id'] = 1;
        $add_city = City::create($data);
        if ($add_city) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit specialty
    public function update_city($id, Request $request)
    {
        $edit_city = City::where('id', $id)->first();
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $edit_city->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }


    // delete city
    function destroy_city(Request $request, $id)
    {
        $city = City::find($id);

        if (!$city) {
            return $this->deleteResponse($request, false, __('City not found'), 404);
        }

        try {
            $city->delete();
        } catch (QueryException $e) {
            return $this->deleteResponse(
                $request,
                false,
                __('Cannot delete this city because it is linked to other data.'),
                422
            );
        }

        return $this->deleteResponse($request, true, trans('messages.deleted'));
    }

    private function deleteResponse(Request $request, bool $status, string $message, int $code = 200)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $code);
        }

        session()->flash($status ? 'success' : 'error', $message);
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $cities = City::where('name_ar', 'LIKE', "%{$query}%")
            ->orWhere('name_en', 'LIKE', "%{$query}%")
            ->get();

        return view('main_admin.partials.cities-list', compact('cities'))->render();
    }
}
