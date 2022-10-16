<?php

namespace AttractCores\LaravelCoreKit\Http\Requests;

use AttractCores\LaravelCoreAuth\Http\Requests\UserRegisterRequest;
use AttractCores\LaravelCoreAuth\Rules\ValidatePasswordStrength;
use AttractCores\LaravelCoreKit\Extensions\Requests\UserProfileRequestExtension;
use Illuminate\Validation\Rule;

/**
 * Class UserProfileRequest
 *
 * @package AttractCores\LaravelCoreKit\Http\Requests
 */
class UserProfileRequest extends UserRegisterRequest
{

    use UserProfileRequestExtension{
        extendedRulesArray as rulesArray;
    }

}
