<?php

namespace Database\Factories;

use App\Models\OrgInformation;
use App\Models\Role;
use App\Models\User;
use App\Models\YoungInformation;
use Illuminate\Support\Str;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class UserFactory extends \AttractCores\LaravelCoreAuth\Database\Factories\UserFactory
{

    public const DEFAULT_PASSWORD = '11111111T&^t';

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return array_merge(parent::definition(), [
            'password'          => self::DEFAULT_PASSWORD,
        ]);
    }

    /**
     * Set field like for request.
     *
     * @return \Database\Factories\UserFactory
     */
    public function forRequest()
    {
        return $this->state([
            'terms_accepted_at' => NULL,
            'email_verified_at' => NULL,
        ]);
    }

}
