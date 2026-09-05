<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
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

        RateLimiter::for('otp', function (Request $request) {
            return Limit::perMinute(15)->by($request->ip());
        });

        RateLimiter::for('registration', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        View::composer('admin.layouts.*', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if (session('admin_logged_in', false)) {
                $userId = session('admin_user_id');
                $notifications = \App\Models\Notification::forAdmins($userId)->latest()->limit(6)->get();
                $unreadCount = \App\Models\Notification::forAdmins($userId)->unread()->count();
            }

            $view
                ->with('adminNotifications', $notifications)
                ->with('adminUnreadNotifCount', $unreadCount);
        });
    }
}
