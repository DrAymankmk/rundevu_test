<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPointRule;
use App\Models\LoyaltyPointTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoyaltyPointRulesController extends Controller
{
    public function index()
    {
        $rules = LoyaltyPointRule::orderByDesc('points')->paginate(20);

        return view('main_admin.loyalty.point-rules', compact('rules'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRule($request);

        $validated['status'] = $request->boolean('status', true);

        LoyaltyPointRule::create($validated);

        session()->flash('success', trans('messages.Added'));

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $rule = LoyaltyPointRule::findOrFail($id);
        $validated = $this->validateRule($request, $rule);
        $validated['status'] = $request->boolean('status');

        $rule->update($validated);

        session()->flash('success', trans('messages.updated'));

        return redirect()->back();
    }

    public function toggleStatus($id, $status)
    {
        $rule = LoyaltyPointRule::findOrFail($id);
        $rule->update(['status' => (int) $status]);

        return response()->json(['message' => trans('messages.updated')]);
    }

    public function destroy($id)
    {
        $rule = LoyaltyPointRule::findOrFail($id);

        if (LoyaltyPointTransaction::where('rule_key', $rule->key)->exists()) {
            session()->flash('failed', trans('main.loyalty_rule_in_use'));

            return redirect()->back();
        }

        $rule->delete();

        session()->flash('success', trans('messages.deleted'));

        return redirect()->back();
    }

    private function validateRule(Request $request, ?LoyaltyPointRule $rule = null): array
    {
        $keyRule = ['required', 'string', 'max:100', 'alpha_dash'];

        if ($rule) {
            $keyRule[] = Rule::unique('loyalty_point_rules', 'key')->ignore($rule->id);
        } else {
            $keyRule[] = Rule::unique('loyalty_point_rules', 'key');
        }

        return $request->validate([
            'key' => $keyRule,
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
            'max_per_day' => 'nullable|integer|min:1',
            'min_words' => 'nullable|integer|min:1',
            'expires_after_months' => 'required|integer|min:1|max:120',
        ]);
    }
}
