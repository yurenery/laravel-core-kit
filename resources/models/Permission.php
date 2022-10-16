<?php

namespace App\Models;

use AttractCores\LaravelCoreAuth\Models\Permission as CorePermission;
use Database\Factories\PermissionFactory;

/**
 * Class Permission
 *
 * @package App\Models
 * Date: 16.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class Permission extends CorePermission
{

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PermissionFactory::new();
    }

}
