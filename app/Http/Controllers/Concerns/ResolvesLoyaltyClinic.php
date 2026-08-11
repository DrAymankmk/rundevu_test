<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Clinic;
use App\Services\ClinicModuleService;
use Illuminate\Support\Facades\Auth;

trait ResolvesLoyaltyClinic
{
    protected function loyaltyClinicId(): int
    {
        return (int) Auth::user()->organizationClinicId();
    }

    /**
     * Clinic ids whose loyalty data the current user may view.
     *
     * @return array<int, int>
     */
    protected function loyaltyScopedClinicIds(): array
    {
        $user = Auth::user();
        $organizationId = $this->loyaltyClinicId();

        if ((int) $user->app_type === 7) {
            return [$organizationId];
        }

        $relatedIds = Clinic::where('parent_id', $organizationId)->pluck('id')->all();

        return array_values(array_unique(array_merge([$organizationId], $relatedIds)));
    }

    protected function forCurrentLoyaltyClinic($query, string $column = 'clinic_id')
    {
        return $query->whereIn($column, $this->loyaltyScopedClinicIds());
    }

    protected function loyaltyOrganization(): ?Clinic
    {
        return Clinic::find($this->loyaltyClinicId());
    }

    protected function ensureLoyaltyEnabled(): void
    {
        if (! app(ClinicModuleService::class)->hasModule(Auth::user(), 'points')) {
            abort(403, trans('main.loyalty_not_enabled'));
        }
    }
}
