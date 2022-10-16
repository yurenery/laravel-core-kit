<?php

namespace Tests\Feature\CRUD\Users;


use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

/**
 * Class ReadUsersTest
 *
 * @version 1.0.0
 * @date    2019-07-28
 * @author  Yure Nery <yurenery@gmail.com>
 */
class ReadUsersTest extends CRUDTestCase
{

    use CRUDOperationTestCase, ServerResponseAssertions;

    /**
     * Return name of the class for testing.
     *
     * @return string
     */
    protected function getCRUDClassUnderTest()
    {
        return "Read Backend Users";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData()
    {
        return [ // 0
            'name'    => 'Admin can read users',
            'route'   => 'backend.v1.users.index',
            'params'  => [],
            'request' => [ 'sort' => 'roles.id', 'expand' => 'roles' ],
            'method'  => 'GET',
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
        // Check cache key for index method.
        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArrayHasKey('roles', $data[0]['relations']);
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get1TestData()
    {
        return [ // 1
            'name'    => 'Admin can read one user',
            'route'   => 'backend.v1.users.show',
            'params'  => [ 'user' => 1 ],
            'request' => [],
            'method'  => 'GET',
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
        $data = $content['data'];
        $this->assertNotEmpty($data);
        $this->assertEquals($data['id'], $this->resolveUser()->findOrFail(1)->getKey());
    }

    /**
     * Return test data for 2 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get2TestData()
    {
        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER ]));

        return [
            'name'    => 'User with perms - sign-in + user - can\'t see',
            'route'   => 'backend.v1.users.show',
            'params'  => [ 'user' => 1 ],
            'request' => [],
            'status'  => 403,
            'method'  => 'GET',
        ];
    }

    /**
     * Return test data for 3 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get3TestData()
    {
        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'Operator can\'t see admins',
            'route'   => 'backend.v1.users.show',
            'params'  => [ 'user' => 1 ],
            'request' => [ 'filter' => json_encode([ 'roles.slug' => CoreRoleContract::CAN_ADMIN ]) ],
            'status'  => 404,
            'method'  => 'GET',
        ];
    }

    /**
     * Return test data for 4 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get4TestData()
    {
        $user = $this->resolveUserFactory()->createOne();
        $user->actAs([ CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER ]);


        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'Operator can see users(not admins).',
            'route'   => 'backend.v1.users.show',
            'params'  => [ 'user' => $user->getKey() ],
            'request' => [],
            'status'  => 200,
            'method'  => 'GET',
        ];
    }

    /**
     * Determine action assertions based on route.
     *
     * @param TestResponse $response
     * @param array        $parameters
     */
    protected function do4TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = $content['data'];
        $this->assertNotEmpty($data);
        $this->assertEquals($data['id'], $parameters['params']['user']);
    }

    /**
     * Return test data for 5 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get5TestData()
    {
        $user = $this->resolveUserFactory()->createOne();
        $user->actAs([ CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER ]);

        $user = $this->resolveUserFactory()->createOne();
        $user->actAs([ CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER ]);

        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'Operator can see users(not admins) list',
            'route'   => 'backend.v1.users.index',
            'params'  => [],
            'request' => [],
            'status'  => 200,
            'method'  => 'GET',
        ];
    }

    /**
     * Determine action assertions based on route.
     *
     * @param TestResponse $response
     * @param array        $parameters
     */
    protected function do5TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = $content['data'];
        $this->assertNotEmpty($data);
        $this->assertCount(2, $data);
    }

    /**
     * Return test data for 6 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get6TestData()
    {
        $user = $this->resolveUserFactory()->createOne();
        $user->actAs([ CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER ]);

        $user = $this->resolveUserFactory()->createOne();
        $user->actAs([ CoreRoleContract::CAN_SIGN_IN, CoreRoleContract::CAN_USER ]);

        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'Operator can see count of users(not admins)',
            'route'   => 'backend.v1.users.index',
            'params'  => [],
            'request' => [ 'limit' => 'count' ],
            'status'  => 200,
            'method'  => 'GET',
        ];
    }

    /**
     * Determine action assertions based on route.
     *
     * @param TestResponse $response
     * @param array        $parameters
     */
    protected function do6TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = $content['data'];
        $this->assertNotEmpty($data);
        $this->assertEquals(2, $data['count']);
    }
}
