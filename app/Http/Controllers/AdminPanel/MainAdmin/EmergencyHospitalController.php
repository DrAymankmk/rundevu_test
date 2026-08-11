<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\EmergencyHospital;
use App\Models\Regions;
use Illuminate\Http\Request;

class EmergencyHospitalController extends Controller
{
    public function index(Request $request)
    {
        $query = EmergencyHospital::with(['city', 'region'])->latest();

        if ($request->q) {
            $query->where(function ($searchQuery) use ($request) {
                $searchQuery->where('name_ar', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }

        $hospitals = $query->paginate(20);
        $cities = City::where('status', 1)->get();

        return view('main_admin.emergency_hospitals.index', compact('hospitals', 'cities'));
    }

    public function create()
    {
        return view('main_admin.emergency_hospitals.form', [
            'hospital' => new EmergencyHospital(),
            'cities' => City::where('status', 1)->get(),
            'regions' => Regions::all(),
            'route' => route('emergency-hospitals.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request, true);
        EmergencyHospital::create($data);

        session()->flash('success', trans('messages.Added'));
        return redirect()->route('emergency-hospitals.index');
    }

    public function edit($id)
    {
        return view('main_admin.emergency_hospitals.form', [
            'hospital' => EmergencyHospital::findOrFail($id),
            'cities' => City::where('status', 1)->get(),
            'regions' => Regions::all(),
            'route' => route('emergency-hospitals.update', $id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, $id)
    {
        $hospital = EmergencyHospital::findOrFail($id);
        $data = $this->validatedData($request);

        if (!$request->hasFile('image')) {
            unset($data['image']);
        }

        $hospital->update($data);

        session()->flash('success', trans('messages.updated'));
        return redirect()->route('emergency-hospitals.index');
    }

    public function destroy($id)
    {
        EmergencyHospital::findOrFail($id)->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->route('emergency-hospitals.index');
    }

    public function toggleStatus($id, $status)
    {
        $hospital = EmergencyHospital::findOrFail($id);
        $hospital->status = (int) $status;
        $hospital->save();

        return response()->json(['status' => true, 'message' => trans('messages.update_status')]);
    }

    private function validatedData(Request $request, $requireImage = false)
    {
        return $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'image' => [$requireImage ? 'required' : 'nullable', 'image', 'max:4096'],
            'city_id' => ['required', 'exists:cities,id'],
            'region_id' => ['nullable', 'exists:regions,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ]);
    }
}
