<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bootstrap-5');

        RateLimiter::for('login', function (Request $request) {
            $key = $request->input('email', $request->input('phone', 'unknown')) . '|' . $request->ip();
            return Limit::perMinute(30)->by($key);
        });
    }
}
