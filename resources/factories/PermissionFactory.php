<?php

namespace Database\Factories;

use App\Models\Permission;

/**
 * Class PermissionFactory
 *
 * @package Database\Factories
 * Date: 16.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class PermissionFactory extends \AttractCores\LaravelCoreAuth\Database\Factories\PermissionFactory
{


    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;
}
