<?php

namespace Tests\Feature\CRUD\Permissions;

use AttractCores\LaravelCoreAuth\Resolvers\CorePermission;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

class ReadPermissionsTest extends CRUDTestCase
{

    use ServerResponseAssertions, CRUDOperationTestCase;

    /**
     * Return name of the class for testing.
     *
     * @return string
     */
    protected function getCRUDClassUnderTest()
    {
        return "Read Backend Permissions";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData()
    {
        return [
            'name'    => 'Admin can read permissions',
            'route'   => 'backend.v1.permissions.index',
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
    public function do0TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = collect($content[ 'data' ]);
        $this->assertNotEmpty($data);
        $this->assertTrue(! ! $data->where('slug', 'admin')->first());
    }

    /**
     * Return test data for 1 assertions
     *
     * @return array
     */
    public function get1TestData()
    {
        return [
            'name'    => 'Admin can read specific permission',
            'route'   => 'backend.v1.permissions.show',
            'params'  => [ 'permission' => 2 ],
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
    public function do1TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = $content[ 'data' ];
        $this->assertNotEmpty($data);
        $this->assertEquals($data[ 'id' ], CorePermission::findOrFail(2)->getKey());
    }

    /**
     * Return test data for 2 assertions
     *
     * @return array
     */
    public function get2TestData()
    {
        return [
            'name'    => 'Admin can read specific permission',
            'route'   => 'backend.v1.permissions.show',
            'params'  => [ 'permission' => 2 ],
            'request' => [],
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
        $this->withAuthorizationToken($this->getRandomUserToken([ 'sign-in' ]));

        return [
            'name'    => 'User with perms - sign-in - can\'t see',
            'route'   => 'backend.v1.permissions.show',
            'params'  => [ 'permission' => 1 ],
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
        $this->withAuthorizationToken($this->getRandomUserToken([ 'backend-sign-in' ]));

        return [
            'name'    => 'User with perms - backend-sign-in + location-owner - can\'t see',
            'route'   => 'backend.v1.permissions.show',
            'params'  => [ 'permission' => 1 ],
            'request' => [],
            'status'  => 403,
            'method'  => 'GET',
        ];
    }

    /**
     * Return test data for 5 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get5TestData()
    {
        $this->withAuthorizationToken($this->getRandomUserToken([ 'backend-sign-in', 'operator' ]));

        return [
            'name'    => 'Operator can\'t see',
            'route'   => 'backend.v1.permissions.show',
            'params'  => [ 'permission' => 1 ],
            'request' => [],
            'status'  => 403,
            'method'  => 'GET',
        ];
    }
}
