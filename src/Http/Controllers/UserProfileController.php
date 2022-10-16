<?php

namespace AttractCores\LaravelCoreKit\Http\Controllers;

use Amondar\RestActions\Actions\UpdateAction;
use AttractCores\LaravelCoreClasses\CoreController;
use AttractCores\LaravelCoreKit\Http\Requests\UserProfileRequest;
use AttractCores\LaravelCoreKit\Http\Resources\UserResource;
use AttractCores\LaravelCoreKit\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Class UserProfileController
 *
 * @version 1.0.0
 * @date    2019-02-18
 * @author  Yure Nery <yurenery@gmail.com>
 */
class UserProfileController extends CoreController
{

    use UpdateAction;

    protected $actions = [
        'update' => [
            'onlyAjax'    => true,
            'request'     => UserProfileRequest::class,
            'transformer' => UserResource::class,
            'repository'  => 'storeOrUpdate',
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
     * Show user profile.
     *
     * @param Request $request
     *
     * @return \AttractCores\LaravelCoreClasses\Libraries\ServerResponse
     */
    public function show(Request $request)
    {
        //Validation with extension
        app(UserProfileRequest::class);

        if ( $request->expectsJson() ) {
            $user = $request->user();

            // Add expands from request.
            $expands = collect(explode(',', $request->expand ?? ''))
                ->filter(function ($expand) use ($user) {
                    return in_array($expand, Arr::except($user->extraFields(), [ 'roles', 'permissions' ]));
                });

            return $this->serverResponse()->resource(
                app(UserResource::class, [
                    'resource' => $user->load($expands->toArray()),
                ])
            );
        }
    }

}