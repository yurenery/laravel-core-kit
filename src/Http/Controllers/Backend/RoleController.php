<?php

namespace AttractCores\LaravelCoreKit\Http\Controllers\Backend;

use Amondar\RestActions\Actions\DestroyAction;
use Amondar\RestActions\Actions\IndexAction;
use Amondar\RestActions\Actions\ShowAction;
use Amondar\RestActions\Actions\StoreAction;
use Amondar\RestActions\Actions\UpdateAction;
use AttractCores\LaravelCoreClasses\CoreController;
use AttractCores\LaravelCoreKit\Http\Requests\RoleRequest;
use AttractCores\LaravelCoreKit\Http\Resources\RoleResource;
use AttractCores\LaravelCoreKit\Repositories\RoleRepository;

/**
 * Class RoleController
 *
 * @version 1.0.0
 * @date    03/12/2018
 * @author  Yure Nery <yurenery@gmail.com>
 */
class RoleController extends CoreController
{

    use IndexAction, ShowAction, StoreAction, UpdateAction, DestroyAction;

    /**
     * Possible actions.
     *
     * @var array
     */
    protected $actions = [
        'index'   => [
            'onlyAjax'    => true,
            'request'     => RoleRequest::class,
            'transformer' => RoleResource::class,
        ],
        'show'    => [
            'onlyAjax'    => true,
            'request'     => RoleRequest::class,
            'transformer' => RoleResource::class,
        ],
        'store'   => [
            'request'     => RoleRequest::class,
            'transformer' => RoleResource::class,
            'repository'  => 'storeOrUpdate',
        ],
        'update'  => [
            'request'     => RoleRequest::class,
            'transformer' => RoleResource::class,
            'repository'  => 'storeOrUpdate',
        ],
        'destroy' => [
            'request' => RoleRequest::class,
        ],
    ];

    /**
     * RoleController constructor.
     *
     * @param RoleRepository $repository
     */
    public function __construct(RoleRepository $repository)
    {

        $this->repository = $repository;
    }

}