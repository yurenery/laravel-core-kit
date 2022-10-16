<?php

namespace AttractCores\LaravelCoreKit\Repositories;

use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreClasses\CoreRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class RoleRepository
 *
 * @version 1.0.0
 * @date    03/12/2018
 * @author  Yure Nery <yurenery@gmail.com>
 */
class RoleRepository extends CoreRepository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return get_class(app(CoreRoleContract::class));
    }

    /**
     * Store or update repository model.
     *
     * @param FormRequest|NULL $request
     * @param array|null       $validated
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeOrUpdate(?FormRequest $request, array $validated = NULL)
    {
        $request = $request ?? request();
        $validated = $validated ?? $request->validated();

        // Fill model.
        $this->model->fill($validated);

        // Call saving hook;
        $this->savingHook($request, $validated);

        // Save model.
        $this->model->save();

        $this->setModelRelations($request, $validated);

        // Call saved hook.
        $this->savedHook($request, $validated);

        return $this->model->load([ 'permissions' ]);
    }

    /**
     * Set model relations and return array of applied changes.
     *
     * @param Request $request
     * @param array   $validated
     *
     * @return array
     */
    public function setModelRelations(Request $request, array $validated)
    {
        $changes = [];

        if ( ! isset($validated[ 'permission_ids' ]) ) {
            $changes[ 'permissions' ] = $this->model->permissions()->sync($validated[ 'permission_ids' ]);
        }

        return $changes;
    }

}