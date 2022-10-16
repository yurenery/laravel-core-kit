<?php

namespace App\Postman;

/**
 * Class BackendUserProfileRequestFactory
 *
 * @package App\Postman
 * Date: 10.01.2022
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
class BackendUserProfileRequestFactory extends ApiUserProfileRequestFactory
{

    /**
     * The name of the factory's corresponding form request or full route name.
     *
     * @var string|null
     */
    protected ?string $request = 'backend.v1.profile.update';

}