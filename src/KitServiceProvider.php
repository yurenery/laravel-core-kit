<?php

namespace AttractCores\LaravelCoreKit;

use AttractCores\LaravelCoreKit\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\Authorize;
use \Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Class KitCoreServiceProvider
 *
 * @version 1.0.0
 * @date    2019-02-18
 * @author  Yure Nery <yurenery@gmail.com>
 */
class KitServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @param \Illuminate\Routing\Router        $router
     * @param \Illuminate\Contracts\Http\Kernel $kernel
     *
     * @return void
     */
    public function boot(Router $router, Kernel $kernel)
    {
        // Set up middlewares.
        $router->aliasMiddleware('can', Authorize::class);
        $router->aliasMiddleware('verified', EnsureEmailIsVerified::class);

        if ( $this->app->runningInConsole() ) {
            $this->publishConfigurations();
            $this->publishTesting();
            $this->publishStarterModules();
            $this->publishPostman();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigurations();

        $this->registerSingletons();
    }

    /**
     * Boot testing.
     */
    protected function publishTesting()
    {
        $this->publishes([
            __DIR__ . '/../tests/Feature' => base_path('tests/Feature'),
        ], 'attract-core-kit-tests');
    }

    /**
     * Boot configuration publications.
     */
    protected function publishPostman()
    {
        $this->publishes([
            __DIR__ . '/../postman' => app_path('Postman'),
        ], 'attract-core-kit-postman-factories');
    }

    /**
     * Boot configuration publications.
     */
    protected function publishConfigurations()
    {

        $this->publishes([
            __DIR__ . '/../config/kit-core.php' => config_path('kit-core.php'),
        ], 'attract-core-kit-core-config');

        $this->publishes([
            __DIR__ . '/../config/kit-routes.php' => config_path('kit-routes.php'),
        ], 'attract-core-kit-routes-config');

        $this->publishes([
            __DIR__ . '/../config/kit-core.php'   => config_path('kit-core.php'),
            __DIR__ . '/../config/kit-routes.php' => config_path('kit-routes.php'),
        ], 'attract-core-kit-config');
    }

    /**
     * Describe all starter modules
     */
    protected function publishStarterModules()
    {
        $this->publishes([
            // Envs
            __DIR__ . '/../resources/env/.env.example'                   => base_path('.env.example'),
            __DIR__ . '/../resources/env/.env.dev'                       => base_path('.env.dev'),
            // Configs block
            __DIR__ . '/../config/cors.php'                              => config_path('cors.php'),
            __DIR__ . '/../config/project.php'                           => config_path('project.php'),
            // Base path's block
            __DIR__ . '/../resources/docs/root-readme-template.md'       => base_path('../readme.md'),
            __DIR__ . '/../resources/docker'                             => base_path('../docker'),
            __DIR__ . '/../resources/terraform'                          => base_path('../terraform'),
            __DIR__ . '/../resources/routes'                             => base_path('routes'),
            __DIR__ . '/../resources/oauth-keys'                         => base_path('storage/oauth-keys'),
            __DIR__ . '/../resources/testing/TestCase.php'               => base_path('tests/TestCase.php'),
            __DIR__ . '/../resources/factories'                          => database_path('factories'),
            // App path's block
            __DIR__ .
            '/../resources/middlewares/TrustProxies.php'                 => app_path('Http/Middleware/TrustProxies.php'),
            __DIR__ .
            '/../resources/providers/RouteServiceProvider.php'           => app_path('Providers/RouteServiceProvider.php'),
            __DIR__ .
            '/../resources/providers/AppServiceProvider.php'             => app_path('Providers/AppServiceProvider.php'),
            __DIR__ . '/../resources/models'                             => app_path('Models'),
            __DIR__ . '/../resources/repositories'                       => app_path('Repositories'),
            __DIR__ . '/../resources/exceptions'                         => app_path('Exceptions'),
            // Views
            __DIR__ . '/../resources/views/vendor'                       => base_path('resources/views/vendor'),
        ], 'attract-core-kit-core-modules');
    }

    /**
     * Merge Kit configuration files.
     */
    protected function mergeConfigurations()
    {
        $path = __DIR__ . '/../config/';
        $this->mergeConfigFrom($path . 'kit-core.php', 'kit-core');
        $this->mergeConfigFrom($path . 'kit-routes.php', 'kit-routes');
    }


    /**
     * Register core singletons.
     */
    protected function registerSingletons()
    {
        //
    }

}
