<?php

namespace Database\Factories;

use App\Models\Role;

/**
 * Class RoleFactory
 *
 * @package Database\Factories
 * Date: 16.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class RoleFactory extends \AttractCores\LaravelCoreAuth\Database\Factories\RoleFactory
{


    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;
}
