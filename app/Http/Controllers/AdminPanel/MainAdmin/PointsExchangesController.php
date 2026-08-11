<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\PointsExchange;
use Illuminate\Http\Request;

class PointsExchangesController extends Controller
{
    public function index()
    {
        $exchanges = PointsExchange::latest()->paginate(20);

        return view('main_admin.points.points_exchanges', compact('exchanges'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateExchange($request);
        $validated['status'] = $request->boolean('status', true);

        PointsExchange::create($validated);

        session()->flash('success', trans('messages.Added'));

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $exchange = PointsExchange::findOrFail($id);
        $validated = $this->validateExchange($request);
        $validated['status'] = $request->boolean('status');

        $exchange->update($validated);

        session()->flash('success', trans('messages.updated'));

        return redirect()->back();
    }

    public function toggleStatus($id, $status)
    {
        $exchange = PointsExchange::findOrFail($id);
        $exchange->update(['status' => (int) $status]);

        return response()->json(['message' => trans('messages.updated')]);
    }

    public function destroy($id)
    {
        PointsExchange::findOrFail($id)->delete();

        session()->flash('success', trans('messages.deleted'));

        return redirect()->back();
    }

    private function validateExchange(Request $request): array
    {
        return $request->validate([
            'points' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);
    }
}
