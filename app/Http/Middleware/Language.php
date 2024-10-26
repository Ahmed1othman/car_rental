<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\App;

class Language
{
    public function handle($request, Closure $next)
    {
        $language = $request->header('Accept-Language')??'en';

        if ($language) {
            App::setLocale($language);
        }

        return $next($request);
    }
}

