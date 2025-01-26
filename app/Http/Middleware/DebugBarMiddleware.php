<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Barryvdh\Debugbar\Facades\Debugbar;

class DebugBarMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Enable Debugbar only for development
        if (config('app.env') !== 'production') {
            Debugbar::enable();
        } else {
            Debugbar::disable();
        }

        return $next($request);
    }
}
