<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ShareErrorsFromSessionWhenNeeded
{
    public function handle(Request $request, Closure $next)
    {
        if (! Session::isStarted() && ! $request->hasSession()) {
            view()->share('errors', new ViewErrorBag(new \Illuminate\Support\MessageBag));

            return $next($request);
        }

        return app(ShareErrorsFromSession::class)->handle($request, $next);
    }
}
