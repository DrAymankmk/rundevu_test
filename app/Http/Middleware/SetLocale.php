<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $default = function_exists('frontend_default_locale')
            ? frontend_default_locale()
            : (string) config('app.fallback_locale', config('app.locale', 'en'));

        $allowed = config('app.locales');
        if (! is_array($allowed) || $allowed === []) {
            $allowed = array_values(array_unique(array_filter([$default, 'ar', 'en'])));
        }

        $segment = strtolower((string) $request->segment(1));
        // Prefer path match so locale logic stays correct behind subdirectory installs.
        $isAdmin = $request->is('admin') || $request->is('admin/*') || $segment === 'admin';

        if ($isAdmin) {
            // Admin keeps session-based locale switching.
            $sessionLang = Session::get('lang');
            $lang = $default;
            if ($sessionLang !== null && $sessionLang !== '') {
                $lang = strtolower(trim((string) $sessionLang));
                if (! in_array($lang, $allowed, true)) {
                    $lang = $default;
                }
            }
            App::setLocale($lang);

            return $next($request);
        }

        $prefixed = function_exists('frontend_prefixed_locales')
            ? frontend_prefixed_locales()
            : ['ar'];

        if (in_array($segment, $prefixed, true) && in_array($segment, $allowed, true)) {
            $lang = $segment;
        } else {
            // Unprefixed frontend URLs always use English (no /en prefix).
            $lang = $default;
        }

        // Do NOT write session('lang') here. Admin locale switching uses that same key;
        // overwriting it on every frontend/beacon request resets dashboard language to English.
        // Frontend locale comes from the URL for this request via App::setLocale().
        App::setLocale($lang);

        return $next($request);
    }
}
