<?php

use App\Services\ClinicModuleService;

if (! function_exists('clinic_has_module')) {
    function clinic_has_module(string $module): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return app(ClinicModuleService::class)->hasModule($user, $module);
    }
}
