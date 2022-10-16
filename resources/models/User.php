<?php

namespace App\Models;

use AttractCores\LaravelCoreAuth\Models\User as CoreUser;
use Database\Factories\UserFactory;

/**
 * Class User
 *
 * @package App\Models
 */
class User extends CoreUser
{

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

}