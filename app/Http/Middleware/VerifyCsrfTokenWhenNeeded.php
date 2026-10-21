<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Runs CSRF verification only when a session is available (form / mutating requests).
 */
class VerifyCsrfTokenWhenNeeded
{
    public function handle(Request $request, Closure $next)
    {
        if (! Session::isStarted() && ! $request->hasSession()) {
            return $next($request);
        }

        // Safe methods never need CSRF; avoid starting work when session was skipped.
        if (in_array(strtoupper($request->method()), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

        return app(VerifyCsrfToken::class)->handle($request, $next);
    }
}
