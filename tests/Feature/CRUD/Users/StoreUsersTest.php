<?php

namespace Tests\Feature\CRUD\Users;

use AttractCores\LaravelCoreAuth\Resolvers\CoreRole;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreKit\Events\AdminCreatedNewUser;
use AttractCores\LaravelCoreKit\Events\UserPasswordChanged;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

class StoreUsersTest extends CRUDTestCase
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
        return "Create Backend Users";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData()
    {
        $rawUser = $this->resolveUserFactory()->raw([ 'password' => $this->pwd ]);

        return [
            'name'    => 'Admin can store user. New user event fired, user received notice',
            'route'   => 'backend.v1.users.store',
            'params'  => [],
            'request' => array_merge($rawUser, [
                'password_confirmation' => $rawUser[ 'password' ],
                'role_ids'              => CoreRole::whereIn('slug', [
                    CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_USER,
                    CoreRoleContract::CAN_OPERATOR, CoreRoleContract::CAN_ADMIN,
                ])->get()->pluck('id')->toArray(),
            ]),
            'status'  => 201,
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

        Event::assertNotDispatched(UserPasswordChanged::class);
        Event::assertDispatched(AdminCreatedNewUser::class,
            function (AdminCreatedNewUser $event) use ($requestData, $data) {
                $this->assertEquals($requestData[ 'password' ], $event->password);

                return true;
            });
    }

    /**
     * Return test data for 1 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get1TestData()
    {
        $rawUser = $this->resolveUserFactory()->raw([ 'password' => $this->pwd ]);

        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'Operator can\'t create users with wrong permissions.',
            'route'   => 'backend.v1.users.store',
            'params'  => [],
            'request' => array_merge($rawUser, [
                'password_confirmation' => $rawUser[ 'password' ],
                'role_ids'              => CoreRole::whereIn('slug',
                    [ CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_ADMIN ])
                                                   ->get()
                                                   ->pluck('id')
                                                   ->toArray(),
            ]),
            'status'  => 422,
        ];
    }

    /**
     * Return test data for 2 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get2TestData()
    {
        $rawUser = $this->resolveUserFactory()->raw([ 'password' => $this->pwd ]);

        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'Operator can create users.',
            'route'   => 'backend.v1.users.store',
            'params'  => [],
            'request' => array_merge($rawUser, [
                'password_confirmation' => $rawUser[ 'password' ],
                'role_ids'              => CoreRole::whereIn('slug',
                    [ CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER ])
                                                   ->get()
                                                   ->pluck('id')
                                                   ->toArray(),
            ]),
            'status'  => 201,
        ];
    }

}
