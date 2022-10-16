<?php

namespace AttractCores\LaravelCoreKit;

use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreAuth\Resolvers\CoreUserContract;
use AttractCores\PostmanDocumentation\PostmanAction;
use Illuminate\Support\Facades\Route;

/**
 * Class CoreKit
 *
 * @package AttractCores\LaravelCoreKit
 * Date: 16.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class CoreKit
{

    /**
     * Add kit core api routes
     *
     * @param string[] $middlewares
     */
    public static function addApiRoutes(
        array $middlewares = [ 'api', 'auth:api', 'check-scopes:api', 'can:' . CorePermissionContract::CAN_SIGN_IN ]
    )
    {
        Route::prefix(config('kit-routes.api.v1.prefix'))
             ->as(config('kit-routes.api.v1.name'))
             ->middleware($middlewares)
             ->namespace('\AttractCores\LaravelCoreKit\Http\Controllers')
             ->group(function () {
                 Route::prefix('profile')->as('profile.')->group(function () {
                     Route::get('/', 'UserProfileController@show')
                          ->name('show')
                          ->aliasedName('Get user profile');
                     Route::put('/', 'UserProfileController@update')
                          ->name('update')
                          ->aliasedName('Update user profile');
                 });
             });
    }

    /**
     * Add kit core backend api routes
     *
     * @param string[] $middlewares
     */
    public static function addBackendApiRoutes(
        array $middlewares = [
            'api', 'auth:api', 'check-scopes:backend', 'can:' . CorePermissionContract::CAN_BACKEND_SIGN_IN,
        ]
    )
    {
        Route::prefix(config('kit-routes.backend.v1.prefix'))
             ->as(config('kit-routes.backend.v1.name'))
             ->middleware($middlewares)
             ->namespace("AttractCores\LaravelCoreKit\Http\Controllers\Backend")
             ->group(function () {
                 Route::middleware([ 'verified:api' ])->group(function () {

                     Route::apiResource('permissions', 'PermissionController')
                          ->only([ 'index', 'show' ])
                          ->postman([
                              'index' => PostmanAction::fresh()
                                                      ->aliasedName('Get list of available permissions'),
                              'show'  => PostmanAction::fresh()
                                                      ->aliasedName('Get one permission resource'),
                          ]);

                     Route::apiResource('roles', 'RoleController')
                          ->postman([
                              'index'   => PostmanAction::fresh()
                                                        ->aliasedName('Get list of available roles')
                                                        ->expands(CoreRoleContract::class),
                              'show'    => PostmanAction::fresh()
                                                        ->aliasedName('Get one role resource')
                                                        ->expands(CoreRoleContract::class),
                              'store'   => PostmanAction::fresh()
                                                        ->aliasedName('Create new role'),
                              'update'  => PostmanAction::fresh()
                                                        ->aliasedName('Update existing role'),
                              'destroy' => PostmanAction::fresh()
                                                        ->aliasedName('Delete existing role'),
                          ]);

                     Route::apiResource('users', 'UserController')
                          ->postman([
                              'index'   => PostmanAction::fresh()
                                                        ->aliasedName('Get list of users')
                                                        ->expands(CoreUserContract::class,),
                              'show'    => PostmanAction::fresh()
                                                        ->aliasedName('Get one user resource')
                                                        ->expands(CoreUserContract::class),
                              'store'   => PostmanAction::fresh()
                                                        ->aliasedName('Create new user'),
                              'update'  => PostmanAction::fresh()
                                                        ->aliasedName('Update existing user'),
                              'destroy' => PostmanAction::fresh()
                                                        ->aliasedName('Delete existing user'),
                          ]);
                 });

                 Route::prefix('profile')->as('profile.')->group(function () {
                     Route::get('/', '\AttractCores\LaravelCoreKit\Http\Controllers\UserProfileController@show')
                          ->name('show')
                          ->aliasedName('Get user profile');
                     Route::put('/', '\AttractCores\LaravelCoreKit\Http\Controllers\UserProfileController@update')
                          ->name('update')
                          ->aliasedName('Update user profile');
                 });
             });
    }

    /**
     * Enable core kit routes.
     *
     * @param string[] $apiMiddlewares
     * @param string[] $backendApiMiddlewares
     */
    public static function enableRoutes(array $apiMiddlewares = [], array $backendApiMiddlewares = [])
    {
        if ( ! empty($apiMiddlewares) ) {
            static::addApiRoutes($apiMiddlewares);
        } else {
            static::addApiRoutes();
        }

        if ( ! empty($backendApiMiddlewares) ) {
            static::addBackendApiRoutes($backendApiMiddlewares);
        } else {
            static::addBackendApiRoutes();
        }
    }

}