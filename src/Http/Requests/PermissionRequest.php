<?php

namespace AttractCores\LaravelCoreKit\Http\Requests;

use AttractCores\LaravelCoreAuth\Http\Requests\CoreRequest;
use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreClasses\CoreFormRequest;

/**
 * Class PermissionRequest
 *
 * @package AttractCores\LaravelCoreKit\Http\Requests
 */
class PermissionRequest extends CoreFormRequest
{

    /**
     * Possible actions
     *
     * @var array
     */
    protected $actions = [
        'get' => [
            'methods'    => [ 'GET' ],
            'permission' => CorePermissionContract::CAN_ADMIN_ACCESS,
        ],
    ];

}
