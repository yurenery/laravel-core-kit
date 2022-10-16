<?php

namespace App\Postman;

use AttractCores\LaravelCoreKit\Http\Requests\RoleRequest;
use AttractCores\PostmanDocumentation\Factory\FormRequestFactory;

/**
 * Class RoleRequestFactory
 *
 * @package App\Postman
 * Date: 10.01.2022
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class RoleRequestFactory extends FormRequestFactory
{

    /**
     * The name of the factory's corresponding form request or full route name.
     *
     * @var string|null
     */
    protected ?string $request = RoleRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function postDefinition() : array
    {
        return [
            'slug'           => $this->faker->slug(2),
            'name_en'        => 'Some role name',
            'permission_ids' => [ '{ID_OF_PERMISSION_TO_APPLY}', '{ANOTHER_ID_OF_PERMISSION_TO_APPLY}' ],
        ];
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function putDefinition() : array
    {
        return $this->postDefinition();
    }

}