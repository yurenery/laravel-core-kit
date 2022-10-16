<?php

namespace App\Repositories;

use AttractCores\LaravelCoreAuth\Contracts\RegistrationContract;
use AttractCores\LaravelCoreKit\Repositories\UserRepository as CoreUserRepository;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class UserRepository
 *
 * @property \App\Models\User $model
 *
 * @package ${NAMESPACE}
 * Date: 16.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class UserRepository extends CoreUserRepository
{

}
