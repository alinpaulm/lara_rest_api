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
        RateLimiter::for('api', function (Request $request) {
            // the 2 expressions should be equivalent. 1st one is from laravel 11, 2nd one is from up to laravel 10
            // return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()); // https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.ternary "As of PHP 8.0.0, the ternary operator is non-associative."
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip()); // https://laravel.com/docs/11.x/helpers#method-optional
        });
    }
}
