<?php

namespace App\Providers;

use App\Models\Account;
use Auth;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        Route::bind('account', function ($value, $route) {
            if ('accounts.members.store' === $route->getName()) {
                return Account::where('id', $value)->firstOrFail();
            }
            $memberships = Auth::user()->memberships->pluck('id');

            return Account::where('id', $value)->whereIn('id', $memberships)->firstOrFail();
        });

        Route::bind('device', function ($deviceId, $route) {
            $account = $route->parameter('account');

            return $account->devices()->where('id', $deviceId)->firstOrFail();
        });

        Route::bind('peripheral', function ($peripheralId, $route) {
            $device  = $route->parameter('device');

            return $device->peripherals()->where('id', $peripheralId)->firstOrFail();
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
