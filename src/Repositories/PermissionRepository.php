<?php

namespace AttractCores\LaravelCoreKit\Repositories;

use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreClasses\CoreRepository;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PermissionRepository
 *
 *
 * @version 1.0.0
 * @date    03/12/2018
 * @author  Yure Nery <yurenery@gmail.com>
 */
class PermissionRepository extends CoreRepository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return get_class(app(CorePermissionContract::class));
    }

}