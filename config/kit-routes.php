<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Api routes settings
    |--------------------------------------------------------------------------
    |
    | Define your project api routes prefixes/names or etc.
    |
    */

    'api' => [
        'v1' => [
            'prefix' => 'api/v1',
            'name'   => 'api.v1.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Backend routes settings
    |--------------------------------------------------------------------------
    |
    | Define your project backend routes prefixes/names or etc.
    |
    */

    'backend' => [
        'v1' => [
            'prefix' => 'backend/v1',
            'name'   => 'backend.v1.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SPA routes aliases
    |--------------------------------------------------------------------------
    |
    | Define your project SPA routes aliases.
    */

    'spa' => [
        'backend'  => [
            'base_url' => env('APP_KIT_CORE_SPA_BACKEND_URL', 'http://starter-kit-backend.test'),
            'name'     => 'spa.backend.',
            'prefix'   => 'spa-backend' // Do not change this.
        ],
        'frontend' => [
            'base_url' => env('APP_KIT_CORE_SPA_FRONTEND_URL', NULL),
            'name'     => 'spa.frontend.',
            'prefix'   => 'spa-frontend' // Do not change this.
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Test routes settings
    |--------------------------------------------------------------------------
    |
    | Define your project test routes prefixes/names prefixes or etc.
    |
    */

    'test' => [
        'v1' => [
            'prefix' => 'test',
            'name'   => 'test.',
        ],
    ],
];
