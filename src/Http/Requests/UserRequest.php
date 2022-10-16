<?php

namespace AttractCores\LaravelCoreKit\Http\Requests;

use AttractCores\LaravelCoreAuth\Http\Requests\UserRegisterRequest;
use AttractCores\LaravelCoreKit\Extensions\Requests\UserRequestExtension;

/**
 * Class UserRequest
 *
 * @package AttractCores\LaravelCoreKit\Http\Requests
 */
class UserRequest extends UserRegisterRequest
{

    use UserRequestExtension{
        extendedRulesArray as rulesArray;
    }

}
