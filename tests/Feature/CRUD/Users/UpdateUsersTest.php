<?php

namespace Tests\Feature\CRUD\Users;

use AttractCores\LaravelCoreAuth\Resolvers\CoreRole;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreKit\Events\UserPasswordChanged;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

class UpdateUsersTest extends CRUDTestCase
{

    use CRUDOperationTestCase, ServerResponseAssertions;

    /**
     * @var null|NotificationFake
     */
    protected ?NotificationFake $fakeNotification = NULL;

    protected $pwd = '11111111T&^t';

    /**
     * Set up each test.
     */
    public function setUp() : void
    {
        parent::setUp();

        // Get fake event facade to detect events firing.
        Event::fake();

        // Get fke notification instance to detect end test notifications.
        $this->fakeNotification = Notification::fake();
    }

    /**
     * Return name of the class for testing.
     *
     * @return string
     */
    protected function getCRUDClassUnderTest()
    {
        return "Update Backend Users";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData()
    {
        $user = $this->resolveUserFactory()->createOne([
            'password' => '11111111',
        ]);

        $user->actAs([ CoreRoleContract::CAN_ADMIN ]);

        return [
            'name'    => 'Admin can update users with password change. User receive notice about pass change.',
            'route'   => 'backend.v1.users.update',
            'params'  => [ 'user' => $user->getKey() ],
            'request' => array_merge($user->toArray(), [
                'password'              => '11111112T^&t',
                'password_confirmation' => '11111112T^&t',
                'role_ids'              => $this->faker->randomElements(CoreRole::whereIn('slug', [
                    CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_USER,
                    CoreRoleContract::CAN_OPERATOR, CoreRoleContract::CAN_ADMIN,
                ])->get()->pluck('id')->toArray(), 2),
            ]),
            'status'  => 200,
            'method'  => 'PUT',
        ];
    }

    /**
     * Determine action assertions based on route.
     *
     * @param TestResponse $response
     * @param array        $parameters
     */
    protected function do0TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = $content[ 'data' ];
        $this->assertNotEmpty($data);
        $requestData = $parameters[ 'request' ];
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals($requestData[ 'email' ], $data[ 'email' ]);
        $this->assertEquals($requestData[ 'name' ], $data[ 'name' ]);
        $this->assertEmpty(collect(Arr::get($data, 'relations.roles', []))
            ->pluck('id')
            ->diff($requestData[ 'role_ids' ])
            ->toArray());

        Event::assertDispatched(UserPasswordChanged::class,
            function (UserPasswordChanged $event) use ($requestData, $data) {
                $this->assertEquals($requestData[ 'password' ], $event->password);
                $this->assertEquals($data[ 'id' ], $event->user->getKey());

                return true;
            });
    }

    /**
     * Return test data for 1 assertions
     *
     * @return array
     */
    public function get1TestData()
    {
        $user = $this->resolveUserFactory()->createOne([
            'password' => '11111111',
        ]);

        $user->actAs([ CoreRoleContract::CAN_USER ]);

        return [
            'name'    => 'Admin can edit users without password change. Notifications silent',
            'route'   => 'backend.v1.users.update',
            'params'  => [ 'user' => $user->getKey() ],
            'request' => array_merge($user->toArray(), [
                'password'              => NULL,
                'password_confirmation' => NULL,
                'role_ids'              => $this->faker->randomElements(CoreRole::whereIn('slug', [
                    CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_USER,
                    CoreRoleContract::CAN_OPERATOR, CoreRoleContract::CAN_ADMIN,
                ])->get()->pluck('id')->toArray(), 2),
            ]),
            'status'  => 200,
            'method'  => 'PUT',
        ];
    }

    /**
     * Determine action assertions based on route.
     *
     * @param TestResponse $response
     * @param array        $parameters
     */
    protected function do1TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = $content[ 'data' ];
        $this->assertNotEmpty($data);
        $requestData = $parameters[ 'request' ];
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals($requestData[ 'email' ], $data[ 'email' ]);
        $this->assertEquals($requestData[ 'name' ], $data[ 'name' ]);
        $this->assertEmpty(collect(Arr::get($data, 'relations.roles', []))
            ->pluck('id')
            ->diff($requestData[ 'role_ids' ])
            ->toArray());

        Event::assertNotDispatched(UserPasswordChanged::class);
    }

    /**
     * Return test data for 2 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get2TestData()
    {
        $user = $this->resolveUserFactory()->createOne();
        $user->actAs([ CoreRoleContract::CAN_USER ]);

        $this->withAuthorizationToken($this->getRandomUserToken([
            CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR,
        ]));

        return [
            'name'    => 'Operator can update users',
            'route'   => 'backend.v1.users.update',
            'params'  => [ 'user' => $user->getKey() ],
            'request' => array_merge($user->toArray(), [
                'password'              => NULL,
                'password_confirmation' => NULL,
                'role_ids'              => $user->roles->pluck('id')->toArray(),
            ]),
            'status'  => 200,
            'method'  => 'PUT',
        ];
    }

    /**
     * Determine action assertions based on route.
     *
     * @param TestResponse $response
     * @param array        $parameters
     */
    protected function do2TestAssertions(TestResponse $response, array $parameters)
    {
        $this->assertSuccessResponse($response);
    }

}
