<?php

namespace AttractCores\LaravelCoreKit\Http\Controllers\Backend;

use Amondar\RestActions\Actions\IndexAction;
use Amondar\RestActions\Actions\ShowAction;
use AttractCores\LaravelCoreClasses\CoreController;
use AttractCores\LaravelCoreKit\Http\Requests\PermissionRequest;
use AttractCores\LaravelCoreKit\Http\Resources\PermissionResource;
use AttractCores\LaravelCoreKit\Repositories\PermissionRepository;

/**
 * Class PermissionController
 *
 * @version 1.0.0
 * @date    03/12/2018
 * @author  Yure Nery <yurenery@gmail.com>
 */
class PermissionController extends CoreController
{

    use IndexAction, ShowAction;

    /**
     * Possible actions.
     *
     * @var array
     */
    protected $actions = [
        'index' => [
            'onlyAjax'    => true,
            'request'     => PermissionRequest::class,
            'transformer' => PermissionResource::class,
        ],
        'show'  => [
            'onlyAjax'    => true,
            'request'     => PermissionRequest::class,
            'transformer' => PermissionResource::class,
        ],
    ];

    /**
     * PermissionController constructor.
     *
     * @param \AttractCores\LaravelCoreKit\Repositories\PermissionRepository $repository
     */
    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

}