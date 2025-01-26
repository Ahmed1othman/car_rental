<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\QueryExecuted;

class PerformanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Only enable query logging in local environment
        if (config('app.env') === 'local') {
            DB::listen(function (QueryExecuted $query) {
                // Log slow queries (more than 1 second)
                if ($query->time > 1000) {
                    \Log::warning('Slow query detected:', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]);
                }
            });
        }

        // Disable some events in production for better performance
        if (config('app.env') === 'production') {
            Event::preventDefault('eloquent.*');
        }
    }
}
