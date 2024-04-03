<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Closure;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';
    public string $backendPrefix = '';
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot()
    {

        $this->configureRateLimiting();

        // $this->routes(function () {
        //     Route::middleware('api')
        //         // ->prefix('api/v1')
        //         ->group(base_path('routes/api.php'));

        // });
        Route::prefix(env("ROUTE_API_PREFIX", ""))
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));

        Route::prefix(env("ROUTE_BACKEND_PREFIX", ""))
            ->middleware(['web'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

//        $this->routes(function () {
//            Route::middleware(['api'])
//                ->as('api:')
//                ->group(
//                    base_path('routes/api.php')
//                );
//        });

//        Route::prefix($this->backendPrefix)
//            ->middleware('web')
//            ->namespace($this->namespace)
//            ->group(base_path('routes/web.php'));
//             $this->registerRoutes(function () {
////            Route::prefix(url_concat($this->backendPrefix, env("ROUTE_API_PREFIX", "api/v1")))
////                ->middleware(['api', 'localization'])
////                ->namespace($this->namespace)
////                ->group(base_path('routes/admin-api.php'));
////
////            Route::prefix(url_concat($this->backendPrefix, env("ROUTE_API_PREFIX", "api/v1")))
////                ->middleware(['api', 'localization'])
////                ->namespace($this->namespace)
////                ->group(base_path('routes/storefront-api.php'));
//
//            Route::prefix($this->backendPrefix)
//                ->middleware('web')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/web.php'));
//
////            if (file_exists(base_path('routes/web.php')) && app()->environment() !== 'production') {
////                Route::prefix(url_concat($this->backendPrefix, 'web'))
////                    ->middleware('web')
////                    ->namespace($this->namespace)
////                    ->group(base_path('routes/web.php'));
////
////            }
//        }, env('ROUTE_BACKEND_PREFIX', ''));
    }

    /**
     * Configure the rate limiters for the application.
     */
    // protected function configureRateLimiting()
    // {
    //     RateLimiter::for('api', function (Request $request) {
    //         return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    //     });
    // }

    protected function configureRateLimiting() : void
    {
        RateLimiter::for(
            name: 'api',
            callback: static fn (Request $request) : Limit => Limit::perMinute(
                maxAttempts: 60,
            )->by(
                key: $request->user()?->id ?: $request->ip(),
            ),
        );
    }

    protected function registerRoutes(Closure $routesCallback, string $prefix = '')
    {
        $this->loadRoutesUsing = $routesCallback;
        $this->backendPrefix = $prefix;

        return $this;
    }
}
