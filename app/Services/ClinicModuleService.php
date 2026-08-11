<?php

namespace App\Services;

use App\Models\Clinic;
use Illuminate\Support\Str;

class ClinicModuleService
{
    public function allModuleKeys(): array
    {
        return array_keys(config('clinic_modules.modules', []));
    }

    public function moduleDefinitions(): array
    {
        return config('clinic_modules.modules', []);
    }

    public function organizationFor(Clinic $clinic): ?Clinic
    {
        $organizationId = Clinic::resolveOrganizationId($clinic->id);

        return $organizationId ? Clinic::find($organizationId) : null;
    }

    /**
     * Effective modules used for access checks.
     *
     * @return array<int, string>
     */
    public function getEnabledModules(Clinic $clinic): array
    {
        if ((int) $clinic->app_type === 6) {
            return $this->allModuleKeys();
        }

        $organization = $this->organizationFor($clinic);

        if (! $organization) {
            return $this->allModuleKeys();
        }

        $stored = $organization->enabled_modules;

        if ($stored === null) {
            return $this->allModuleKeys();
        }

        $valid = array_values(array_intersect(
            array_map('strval', (array) $stored),
            $this->allModuleKeys()
        ));

        return $valid;
    }

    /**
     * Modules explicitly stored on the organization (for forms/display).
     *
     * @return array<int, string>
     */
    public function getStoredModules(Clinic $organization): array
    {
        if ($organization->enabled_modules === null) {
            return [];
        }

        return array_values(array_intersect(
            array_map('strval', (array) $organization->enabled_modules),
            $this->allModuleKeys()
        ));
    }

    public function hasModule(Clinic $clinic, string $module): bool
    {
        if ((int) $clinic->app_type === 6) {
            return true;
        }

        if ($module === 'points' && ! $this->isPointsFeatureEnabled($clinic)) {
            return false;
        }

        return in_array($module, $this->getEnabledModules($clinic), true);
    }

    public function isPointsFeatureEnabled(Clinic $clinic): bool
    {
        if ((int) $clinic->app_type === 6) {
            return true;
        }

        $organization = $this->organizationFor($clinic);

        return $organization ? (bool) $organization->points_enabled : false;
    }

    public function hasRestrictedModules(Clinic $clinic): bool
    {
        if ((int) $clinic->app_type === 6) {
            return false;
        }

        $organization = $this->organizationFor($clinic);

        return $organization !== null && $organization->enabled_modules !== null;
    }

    public function loginRedirectRoute(Clinic $clinic): string
    {
        if ($this->hasModule($clinic, 'clinic_admin')) {
            return 'admin.dashboard';
        }

        if ($this->hasModule($clinic, 'points')) {
            $routes = config('clinic_modules.modules.points.login_route_by_app_type', []);
            $appType = (int) $clinic->app_type;

            if (! empty($routes[$appType])) {
                return $routes[$appType];
            }

            return 'loyalty.dashboard';
        }

        return 'profile';
    }

    public function requiredModuleForRoute(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        foreach ($this->moduleDefinitions() as $key => $definition) {
            foreach ($definition['route_patterns'] ?? [] as $pattern) {
                if ($this->routeMatchesPattern($routeName, $pattern)) {
                    return $key;
                }
            }
        }

        return null;
    }

    public function isAlwaysAllowedRoute(?string $routeName): bool
    {
        if (! $routeName) {
            return true;
        }

        foreach (config('clinic_modules.always_allowed_route_patterns', []) as $pattern) {
            if ($this->routeMatchesPattern($routeName, $pattern)) {
                return true;
            }
        }

        return false;
    }

    public function canAccessRoute(Clinic $clinic, ?string $routeName): bool
    {
        if ((int) $clinic->app_type === 6) {
            return true;
        }

        if ($routeName === 'admin.dashboard') {
            return $this->hasModule($clinic, 'clinic_admin');
        }

        if ($this->isAlwaysAllowedRoute($routeName)) {
            return true;
        }

        $required = $this->requiredModuleForRoute($routeName);

        if ($required === null) {
            return ! $this->hasRestrictedModules($clinic);
        }

        return $this->hasModule($clinic, $required);
    }

    /**
     * @param  array<int, string>  $modules
     * @return array<int, string>
     */
    public function sanitizeEnabledModules(array $modules): array
    {
        return array_values(array_unique(array_intersect(
            array_map('strval', $modules),
            $this->allModuleKeys()
        )));
    }

    /**
     * @param  array<int, string>  $modules
     */
    public function syncOrganizationModules(Clinic $organization, array $modules, ?bool $pointsEnabled = null): void
    {
        $sanitized = $this->sanitizeEnabledModules($modules);

        if ($pointsEnabled === null) {
            $pointsEnabled = in_array('points', $sanitized, true);
        }

        if ($pointsEnabled && ! in_array('points', $sanitized, true)) {
            $sanitized[] = 'points';
            $sanitized = array_values(array_unique($sanitized));
        }

        if (! $pointsEnabled) {
            $sanitized = array_values(array_filter($sanitized, fn ($key) => $key !== 'points'));
        }

        $organization->update([
            'points_enabled' => (int) $pointsEnabled,
            'enabled_modules' => $sanitized,
        ]);
    }

    public function togglePointsEnabled(Clinic $organization, bool $pointsEnabled): void
    {
        $current = $organization->enabled_modules;

        if ($current === null) {
            if ($pointsEnabled) {
                $organization->update(['points_enabled' => 1]);

                return;
            }

            $modules = array_values(array_filter(
                $this->allModuleKeys(),
                fn ($key) => $key !== 'points'
            ));
            $this->syncOrganizationModules($organization, $modules, false);

            return;
        }

        $modules = array_values(array_unique(array_map('strval', (array) $current)));

        if ($pointsEnabled) {
            $modules[] = 'points';
        } else {
            $modules = array_values(array_filter($modules, fn ($key) => $key !== 'points'));
        }

        $this->syncOrganizationModules($organization, $modules, $pointsEnabled);
    }

    protected function routeMatchesPattern(string $routeName, string $pattern): bool
    {
        if ($pattern === $routeName) {
            return true;
        }

        if (Str::endsWith($pattern, '.*')) {
            $prefix = Str::beforeLast($pattern, '.*');

            return Str::startsWith($routeName, $prefix);
        }

        return false;
    }
}
