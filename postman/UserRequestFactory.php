<?php

namespace App\Postman;

use AttractCores\LaravelCoreKit\Http\Requests\UserRequest;
use AttractCores\PostmanDocumentation\Factory\FormRequestFactory;

/**
 * Class UserRequestFactory
 *
 * @package App\Postman
 * Date: 10.01.2022
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class UserRequestFactory extends FormRequestFactory
{

    /**
     * The name of the factory's corresponding form request or full route name.
     *
     * @var string|null
     */
    protected ?string $request = UserRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function postDefinition() : array
    {
        $pwd = $this->faker->password(10);

        return [
            'email'                 => $this->faker->safeEmail,
            'password'              => $pwd,
            'password_confirmation' => $pwd,
            'name'                  => $this->faker->name,
            'role_ids'              => [ '{ID_OF_ROLE_TO_APPLY}', '{ANOTHER_ID_OF_ROLE_TO_APPLY}' ],
        ];
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function putDefinition() : array
    {
        return array_merge($this->postDefinition(), [
            'password'              => NULL,
            'password_confirmation' => NULL,
        ]);
    }

}