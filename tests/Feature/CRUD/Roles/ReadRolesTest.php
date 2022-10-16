<?php

namespace Tests\Feature\CRUD\Roles;

use AttractCores\LaravelCoreAuth\Resolvers\CoreRole;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

/**
 * Class ReadRolesTest
 *
 * @version 1.0.0
 * @date    2019-07-28
 * @author  Yure Nery <yurenery@gmail.com>
 */
class ReadRolesTest extends CRUDTestCase
{

    use CRUDOperationTestCase, ServerResponseAssertions;

    /**
     * Return name of the class for testing.
     *
     * @return string
     */
    protected function getCRUDClassUnderTest()
    {
        return "Read Backend Roles";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData()
    {
        return [
            'name'    => 'Admin can read roles',
            'route'   => 'backend.v1.roles.index',
            'params'  => [],
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
    protected function do0TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = collect($content['data']);
        $this->assertNotEmpty($data);
        $this->assertTrue(! ! $data->where('slug', CoreRoleContract::CAN_ADMIN)->first());
    }

    /**
     * Return test data for 1 assertions
     *
     * @return array
     */
    public function get1TestData()
    {
        return [
            'name'    => 'Admin can read one role',
            'route'   => 'backend.v1.roles.show',
            'params'  => [ 'role' => 2 ],
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
        $this->assertEquals($data['id'], CoreRole::findOrFail(2)->getKey());
    }

    /**
     * Return test data for 2 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get2TestData()
    {
        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_SIGN_IN ]));

        return [
            'name'    => 'User with perms - sign-in - can\'t see',
            'route'   => 'backend.v1.roles.show',
            'params'  => [ 'role' => 1 ],
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
        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN ]));

        return [
            'name'    => 'User with perms - backend-sign-in - can\'t see',
            'route'   => 'backend.v1.roles.show',
            'params'  => [ 'role' => 1 ],
            'request' => [],
            'status'  => 403,
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
        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'User with perms - backend-sign-in + operator - CAN see',
            'route'   => 'backend.v1.roles.show',
            'params'  => [ 'role' => 1 ],
            'request' => [],
            'status'  => 200,
            'method'  => 'GET',
        ];
    }

}
