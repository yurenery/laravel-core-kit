<?php

namespace Tests\Feature\Profile;

use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreKit\Events\UserPasswordChanged;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

class ProfileTest extends CRUDTestCase
{

    use CRUDOperationTestCase, ServerResponseAssertions;

    /**
     * @var null|NotificationFake
     */
    protected ?NotificationFake $fakeNotification = NULL;

    protected ?string $userEmail = NULL;

    /**
     * Set up each test case.
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
        return "PROFILE INTERACTS";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData(){
        return [
            'name'    => 'Current user gets his profile BACKEND API.',
            'route'   => 'backend.v1.profile.show',
            'method'  => 'GET',
            'params'  => [],
            'request' => [],
            'status'  => 200,
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
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('permissions', $data);
        $this->assertArrayHasKey('roles_names', $data);
        $this->assertEquals($data[ 'email' ], config('kit-auth.start-user.email'));
    }

    /**
     * Return test data for 1 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get1TestData(){
        $this->withAuthorizationToken($token = $this->getRandomUserToken([
            CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER,
        ]));
        $this->userEmail = $this->getUserEmailByToken($token);

        return [
            'name'    => 'Current user gets his profile API.',
            'route'   => 'api.v1.profile.show',
            'method'  => 'GET',
            'params'  => [],
            'request' => [ ],
            'status'  => 200,
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
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('permissions', $data);
        $this->assertArrayHasKey('roles_names', $data);
        $this->assertEquals($data[ 'email' ], $this->userEmail);
    }

    /**
     * Return test data for 2 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get2TestData(){
        $this->withAuthorizationToken($token = $this->getRandomUserToken([
            CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER,
        ]));
        $this->userEmail = $this->getUserEmailByToken($token);

        $user = $this->resolveUser()->byEmail($this->userEmail)->first();

        return [
            'name'    => 'Current user updates profile API. Password changed - event fired.',
            'route'   => 'api.v1.profile.update', 'method' => 'PUT', 'params' => [],
            'request' => array_merge($user->toArray(), [
                'name'                  => 'New Name',
                'password'              => '111111111111111T^&t',
                'password_confirmation' => '111111111111111T^&t',
                'email'                 => 'a@a.ru',
            ]),
            'status'  => 200,
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
        $content = $this->assertSuccessResponse($response);
        $data = $content[ 'data' ];
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('permissions', $data);
        $this->assertArrayHasKey('roles_names', $data);
        $this->assertEquals($data[ 'email' ], 'a@a.ru');
        $this->assertEquals($data[ 'name' ], $parameters[ 'request' ][ 'name' ]);

        $requestData = $parameters[ 'request' ];

        Event::assertDispatched(UserPasswordChanged::class,
            function (UserPasswordChanged $event) use ($requestData, $data) {
                $this->assertEquals($requestData[ 'password' ], $event->password);
                $this->assertEquals($data[ 'id' ], $event->user->getKey());

                return true;
            });
    }

    /**
     * Return test data for 3 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get3TestData(){
        $this->withAuthorizationToken($token = $this->getRandomUserToken([
            CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_ADMIN,
        ]));
        $this->userEmail = $this->getUserEmailByToken($token);

        $user = $this->resolveUser()->byEmail($this->userEmail)->first();

        return [
            'name'    => 'Current user(ADMIN) updates profile API. Without password changing.',
            'route'   => 'api.v1.profile.update',
            'method'  => 'PUT',
            'params'  => [],
            'request' => array_merge($user->toArray(), [
                'name'                  => 'New Name',
                'password'              => NULL,
                'password_confirmation' => NULL,
            ]),
            'status'  => 200,
        ];
    }

    /**
     * Determine action assertions based on route.
     *
     * @param TestResponse $response
     * @param array        $parameters
     */
    protected function do3TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = $content[ 'data' ];
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('permissions', $data);
        $this->assertArrayHasKey('roles_names', $data);
        $this->assertEquals($data[ 'email' ], $parameters[ 'request' ][ 'email' ]);
        $this->assertEquals($data[ 'name' ], $parameters[ 'request' ][ 'name' ]);
        Event::assertNotDispatched(UserPasswordChanged::class);
    }

}
