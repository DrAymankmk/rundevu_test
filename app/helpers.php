<?php

use App\Services\ClinicModuleService;

if (! function_exists('admin_language_switch_url')) {
    function admin_language_switch_url(string $lang): string
    {
        $current = request()->getRequestUri();
        if (strpos($current, '/changeLanguageAdmin') !== false || strpos($current, '/admin/login') !== false) {
            $current = '/admin/dashboard';
        }

        return url('/changeLanguageAdmin/' . $lang) . '?redirect=' . rawurlencode($current);
    }
}

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
