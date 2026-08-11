<?php

namespace App\Http\Middleware;

use Closure;
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
        $default = (string) config('app.locale', 'en');
        $sessionLang = Session::get('lang');

        if ($sessionLang !== null && $sessionLang !== '') {
            $lang = strtolower(trim((string) $sessionLang));
            $allowed = config('app.locales');
            if (is_array($allowed) && $allowed !== [] && ! in_array($lang, $allowed, true)) {
                $lang = $default;
            }
            app()->setLocale($lang);
        } else {
            app()->setLocale($default);
        }

        return $next($request);
    }
}
