<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Currency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $currency = $request->header('Currency');

        if ($currency) {
            app()->singleton('currency_id', function () use ($currency) {
                return $currency;
            });
        }else
            app()->singleton('currency_id', function () use ($currency) {
                return 1;
            });

        return $next($request);
    }
}
