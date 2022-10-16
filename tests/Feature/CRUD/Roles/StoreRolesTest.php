<?php

namespace Tests\Feature\CRUD\Roles;

use AttractCores\LaravelCoreAuth\Resolvers\CorePermission;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRole;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

class StoreRolesTest extends CRUDTestCase
{

    use CRUDOperationTestCase, ServerResponseAssertions;

    /**
     * Return name of the class for testing.
     *
     * @return string
     */
    protected function getCRUDClassUnderTest()
    {
        return "Create Backend Roles";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData()
    {
        return [
            'name'    => 'Admin can store roles',
            'route'   => 'backend.v1.roles.store',
            'params'  => [],
            'request' => array_merge(CoreRole::factory()->raw(), [
                'permission_ids' => $this->faker->randomElements(CorePermission::all()->pluck('id')->toArray(), 2),
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
        $this->assertEquals($requestData[ 'slug' ], $data[ 'slug' ]);
        $this->assertEquals($requestData[ 'name_en' ], $data[ 'name_en' ]);
        $this->assertEmpty(collect(Arr::get($data, 'relations.permissions', []))
            ->pluck('id')
            ->diff($requestData[ 'permission_ids' ]));
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
            'name'    => 'Operator can\'t store roles',
            'route'   => 'backend.v1.roles.store',
            'params'  => [],
            'request' => [],
            'status'  => 403,
        ];
    }

}
