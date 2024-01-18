<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::prefix('b2b/api')
                ->middleware('api')
                ->namespace('B2BApp\Http\Controllers')
                ->group(base_path('routes/b2b-api.php'));

            Route::domain('admin.' . env('APP_URL'))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // Route::domain('b2b.' . env('APP_URL'))
            //     ->middleware('b2b')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/b2b.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/supplier.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/front-web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(100000)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
