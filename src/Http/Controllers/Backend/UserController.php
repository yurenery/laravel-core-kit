<?php

namespace AttractCores\LaravelCoreKit\Http\Controllers\Backend;

use Amondar\RestActions\Actions\DestroyAction;
use Amondar\RestActions\Actions\IndexAction;
use Amondar\RestActions\Actions\ShowAction;
use Amondar\RestActions\Actions\StoreAction;
use Amondar\RestActions\Actions\UpdateAction;
use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreClasses\CoreController;
use AttractCores\LaravelCoreKit\Http\Requests\UserRequest;
use AttractCores\LaravelCoreKit\Http\Resources\UserResource;
use AttractCores\LaravelCoreKit\Repositories\UserRepository;
use Illuminate\Http\Request;

/**
 * Class UserController
 *
 * @version 1.0.0
 * @date    03/12/2018
 * @author  Yure Nery <yurenery@gmail.com>
 */
class UserController extends CoreController
{

    use IndexAction, ShowAction, StoreAction, UpdateAction, DestroyAction;

    /**
     * Main request class.
     *
     * @var string
     */
    protected $restActionsRequest = UserRequest::class;

    /**
     * Possible actions.
     *
     * @var array
     */
    protected $actions = [
        'index'   => [
            'onlyAjax'    => true,
            'transformer' => UserResource::class,
        ],
        'show'    => [
            'onlyAjax'    => true,
            'transformer' => UserResource::class,
        ],
        'store'   => [
            'transformer' => UserResource::class,
            'repository'  => 'storeOrUpdate',
        ],
        'update'  => [
            'transformer' => UserResource::class,
            'repository'  => 'storeOrUpdate',
        ],
        'destroy' => [
            'repository' => 'delete',
        ],
    ];

    /**
     * UserController constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {

        $this->repository = $repository;
    }

    /**
     * Return base filter builder.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getBaseFilterQuery(Request $request)
    {
        if ( $request->user()->isOperator() ) {
            return $this->repository->newQuery()
                                    ->doesntHavePermission([
                                        CorePermissionContract::CAN_OPERATOR_ACCESS, CorePermissionContract::CAN_ADMIN_ACCESS,
                                    ]);
        }

        return $this->repository->newQuery();
    }

}
