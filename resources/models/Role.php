<?php

namespace App\Models;

use AttractCores\LaravelCoreAuth\Models\Role as CoreRole;
use Database\Factories\RoleFactory;

/**
 * Class Role
 *
 * @package App\Models
 * Date: 16.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class Role extends CoreRole
{

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return RoleFactory::new();
    }

}
