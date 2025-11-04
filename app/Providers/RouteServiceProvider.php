<?php

namespace App\Providers;


use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class RouteServiceProvider
{
    public function boot()
    {
        $this->configureRateLimiting();

        // Existing code ...
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('chat', function () {
            return Limit::perMinute(10); // প্রতি মিনিটে 10 request
        });

        // Existing api limiter
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

}
