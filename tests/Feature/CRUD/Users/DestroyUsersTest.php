<?php

namespace Tests\Feature\CRUD\Users;


use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreAuth\Resolvers\CoreUser;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

class DestroyUsersTest extends CRUDTestCase
{

    use CRUDOperationTestCase, ServerResponseAssertions;

    /**
     * @var null|NotificationFake
     */
    protected ?NotificationFake $fakeNotification = NULL;

    protected $user = null;

    /**
     * Return name of the class for testing.
     *
     * @return string
     */
    protected function getCRUDClassUnderTest()
    {
        return "Backend Destroy Users";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData()
    {
        $this->user = $this->resolveUserFactory()->createOne();

        return [
            'name'    => 'Admin can destroy the user',
            'route'   => 'backend.v1.users.destroy',
            'params'  => [ 'user' => $this->user->getKey() ],
            'request' => [],
            'status'  => 200,
            'method'  => 'DELETE',
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
        $data = $content['data'];
        $this->assertEmpty($data);

        // Check for soft deleting.
        $user = CoreUser::withTrashed()->where('id', $parameters['params']['user'])->first();
        $this->assertNotNull($user);
        $this->assertNotEquals($user->email, $this->user->email);
    }

    /**
     * Return test data for 1 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get1TestData()
    {
        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'Anybody can\'t destroy protected user. ID = 1',
            'route'   => 'backend.v1.users.destroy',
            'params'  => [ 'user' => 1 ],
            'request' => [],
            'status'  => 403,
            'method'  => 'DELETE',
        ];
    }

}
