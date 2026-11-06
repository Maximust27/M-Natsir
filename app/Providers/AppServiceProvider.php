<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin-login', function (Request $request): array {
            $identity = strtolower(trim((string) $request->input('email', '')));
            $ip = (string) $request->ip();

            return [
                Limit::perMinute(5)->by(hash('sha256', $identity.'|'.$ip)),
                Limit::perMinute(30)->by($ip),
            ];
        });
    }
}
