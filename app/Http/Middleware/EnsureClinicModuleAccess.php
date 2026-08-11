<?php

namespace App\Http\Middleware;

use App\Services\ClinicModuleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureClinicModuleAccess
{
    public function __construct(
        protected ClinicModuleService $modules
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (! $this->modules->canAccessRoute($user, $routeName)) {
            if ($this->modules->hasModule($user, 'points')) {
                return redirect()
                    ->route($this->modules->loginRedirectRoute($user))
                    ->with('failed', trans('main.module_access_denied'));
            }

            abort(403, trans('main.module_access_denied'));
        }

        return $next($request);
    }
}
