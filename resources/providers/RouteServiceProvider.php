<?php

namespace App\Providers;

use AttractCores\LaravelCoreAuth\CoreAuth;
use AttractCores\LaravelCoreKit\CoreKit;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

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
        if ( ! $this->app->runningUnitTests() ) {
            Passport::hashClientSecrets();
        }

        // Configure rate limiting.
        $this->configureRateLimiting();

        // Enable Core Routes.
        CoreKit::enableRoutes();
        CoreAuth::enableRoutes();

        // Map application routes.
        $this->mapRoutesForAll();
        $this->mapRoutesForApi();
        $this->mapRoutesForBackend();
        $this->mapSpaRoutes();
        $this->mapTestRoutes();
    }

    /**
     * Map routes for anybody, guests included
     */
    protected function mapRoutesForAll()
    {
        Route::prefix(config('kit-routes.api.v1.prefix'))
             ->as(config('kit-routes.api.v1.name'))
             ->middleware([ 'api', 'auth-api-client' ])
             ->namespace($this->namespace)
             ->group(function () {
                 // API client authorized route files.
                 //require base_path('routes/api/static-pages.php');
             });
    }

    /**
     * Map routes for api requests
     */
    protected function mapRoutesForApi()
    {
        \Route::prefix(config('kit-routes.api.v1.prefix'))
              ->as(config('kit-routes.api.v1.name'))
              ->middleware([ 'api', 'auth:api', 'check-scopes:api', 'verified:api' ])
              ->namespace($this->namespace)
              ->group(function () {
                  // API authorized route files.
                  //require base_path('routes/api/test.php');
              });
    }

    /**
     * Map routes for Backend api requests
     */
    protected function mapRoutesForBackend()
    {
        \Route::prefix(config('kit-routes.backend.v1.prefix'))
              ->as(config('kit-routes.backend.v1.name'))
              ->middleware([ 'api', 'auth:api', 'check-scopes:backend', 'verified:api' ])
              ->namespace($this->namespace)
              ->group(function () {
                  // Backend authorized route files.
                  //require base_path('routes/backend/test.php');
              });
    }

    /**
     * Define the "spa" routes for the application.
     *
     * @return void
     */
    protected function mapSpaRoutes()
    {
        Route::prefix(config('kit-routes.spa.backend.prefix'))
             ->as(config('kit-routes.spa.backend.name'))
             ->namespace('\AttractCores\LaravelCoreKit\Http\Controllers')
             ->group(function () {
                 // Describe ADMIN PANEL FRONTEND spa routes.
                 Route::get('admin/password/reset', 'SpaFakeRouteController@fake')->name('passwords.reset');
             });

        \Route::prefix(config('kit-routes.spa.frontend.prefix'))
              ->as(config('kit-routes.spa.frontend.name'))
              ->namespace('\AttractCores\LaravelCoreKit\Http\Controllers')
              ->group(function () {
                  // Describe FRONTEND spa routes.
                  Route::get('password/reset', 'SpaFakeRouteController@fake')->name('passwords.reset');
                  Route::get('sign-up/email-verification', 'SpaFakeRouteController@fake')->name('email.verification');
              });
    }

    /**
     * Define the "test" routes for the application.
     *
     * @return void
     */
    protected function mapTestRoutes()
    {
        if ( $this->app->environment([ 'local' ]) ) {
            Route::prefix('test')
                 ->as('test.')
                 ->group(function () {
                     require base_path('routes/dev.php');
                 });
        }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api-bearer', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->bearerToken());
        });

        RateLimiter::for('toggle-actions', function (Request $request) {
            return Limit::perMinute(30)->by(optional($request->user())->id ?: $request->bearerToken());
        });
    }

}
