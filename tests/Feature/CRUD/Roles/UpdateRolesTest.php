<?php

namespace Tests\Feature\CRUD\Roles;

use AttractCores\LaravelCoreAuth\Resolvers\CorePermission;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRole;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreTestBench\CRUDOperationTestCase;
use AttractCores\LaravelCoreTestBench\ServerResponseAssertions;
use Illuminate\Testing\TestResponse;
use Tests\Feature\CRUD\CRUDTestCase;

class UpdateRolesTest extends CRUDTestCase
{

    use CRUDOperationTestCase, ServerResponseAssertions;

    /**
     * Return name of the class for testing.
     *
     * @return string
     */
    protected function getCRUDClassUnderTest()
    {
        return "Update Backend Roles";
    }

    /**
     * Return test data for 0 assertions
     *
     * @return array
     */
    public function get0TestData()
    {
        $role = CoreRole::factory()->createOne();

        return [
            'name'    => 'Admin can update role',
            'route'   => 'backend.v1.roles.update',
            'method'  => 'PUT',
            'params'  => [ 'role' => $role->getKey() ],
            'request' => array_merge($role->toArray(), [
                'slug'           => 'test-me',
                'name_en'        => "i'll test you",
                'permission_ids' => $this->faker->randomElements(CorePermission::all()->pluck('id')->toArray(), 2),
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
    protected function do0TestAssertions(TestResponse $response, array $parameters)
    {
        $content = $this->assertSuccessResponse($response);
        $data = $content[ 'data' ];
        $this->assertNotEmpty($data);
        $requestData = $parameters[ 'request' ];
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals($requestData[ 'slug' ], $data[ 'slug' ]);
        $this->assertEquals($requestData[ 'name_en' ], $data[ 'name_en' ]);
        $this->assertTrue(collect(\Arr::get($data, 'relations.permissions', []))
            ->pluck('id')
            ->diff($requestData[ 'permission_ids' ])
            ->isEmpty());
    }

    /**
     * Return test data for 1 assertions
     *
     * @return array
     * @throws \Throwable
     */
    public function get1TestData()
    {
        $role = CoreRole::factory()->createOne();

        $this->withAuthorizationToken($this->getRandomUserToken([ CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_OPERATOR ]));

        return [
            'name'    => 'Operator can\'t update role',
            'route'   => 'backend.v1.roles.update',
            'method'  => 'PUT',
            'params'  => [ 'role' => $role->getKey() ],
            'request' => [],
            'status'  => 403,
        ];
    }

}
