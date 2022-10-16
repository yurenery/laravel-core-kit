<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use AttractCores\LaravelCoreAuth\Notifications\ResetPassword;
use AttractCores\LaravelCoreAuth\Notifications\VerifyEmail;
use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreAuth\Resolvers\CoreUserContract;
use AttractCores\LaravelCoreKit\Libraries\MailMessage;
use AttractCores\LaravelCoreKit\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->extendCoreKitRegistrations();
        $this->extendsCoreKitBinds();
        $this->extendDefaultMailSettings();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ( $this->app->runningUnitTests() ) {
            \DB::enableQueryLog();
        }

        $appName = config('app.name');
        MailMessage::setSubjectPattern("[$appName] %s.");
        MailMessage::customSalutation(__("Best regards, :app_name Team.", [ 'app_name' => config('app.name') ]));
    }

    /**
     * Add extended singletons for Kit Core.
     */
    protected function extendCoreKitRegistrations()
    {
        $this->app->singleton(CoreUserContract::class, function () {
            return new User();
        });

        $this->app->singleton(CorePermissionContract::class, function () {
            return new Permission();
        });

        $this->app->singleton(CoreRoleContract::class, function () {
            return new Role();
        });
    }

    /**
     * Re bind Core Kit classes.
     */
    protected function extendsCoreKitBinds()
    {
        // Replace core user repository.
        $this->app->bind(UserRepository::class, \App\Repositories\UserRepository::class);
    }

    /**
     * Extends Default Mail settings
     */
    protected function extendDefaultMailSettings()
    {
        ResetPassword::$mailDriverCallback = function () {
            return new MailMessage();
        };

        VerifyEmail::$mailDriverCallback = function () {
            return new MailMessage();
        };

        ResetPassword::$createUrlCallback = function ($user, $tokens, $requestSide) {
            return spaRoute('passwords.reset', [
                'email'   => $user->email,
                'w_token' => $tokens[ 'web' ],
                'm_code'  => $tokens[ 'mobile' ],
            ], $requestSide);
        };

        VerifyEmail::$createUrlCallback = function ($user, $tokens, $requestSide) {
            return spaRoute('email.verification', [
                'email'   => $user->email,
                'w_token' => $tokens[ 'web' ],
                'm_code'  => $tokens[ 'mobile' ],
            ]);
        };
    }

}
