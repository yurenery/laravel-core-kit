<?php

namespace AttractCores\LaravelCoreKit\Repositories;

use AttractCores\LaravelCoreAuth\Resolvers\CoreUserContract;
use AttractCores\LaravelCoreClasses\CoreRepository;
use AttractCores\LaravelCoreKit\Events\AdminCreatedNewUser;
use AttractCores\LaravelCoreKit\Events\UserPasswordChanged;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use ShortCode\Random as ShortCode;

/**
 * Class UserRepository
 *
 * @property \AttractCores\LaravelCoreAuth\Models\User model
 *
 * @version 1.0.0
 * @date    03/12/2018
 * @author  Yure Nery <yurenery@gmail.com>
 */
class UserRepository extends CoreRepository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return get_class(app(CoreUserContract::class));
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

        // Fill model data.
        $this->model
            ->fill($validated);

        // Call saving hook;
        $this->savingHook($request, $validated);

        // Save model.
        $this->model->save();

        // Run model relations set up.
        $this->setModelRelations($request, $validated);

        // Call saved hook.
        $this->savedHook($request, $validated);

        return $this->model->load('roles');
    }

    /**
     * Delete repository model.
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete()
    {
        // Save user with soft deleted changed email.
        $this->model->forceFill([
            'email' => $this->model->softDeleteEmail(),
        ])->saveQuietly();

        return parent::delete();
    }

    /**
     * Run saving hook. Dirty fields should be available.
     * Can't be used as public, cuz changes applied without DB saving.
     *
     * @param Request $request
     * @param array   $validated
     *
     * @return array
     */
    protected function savingHook(Request $request, array &$validated) : array
    {
        // Accept terms, cuz we creating an account now.
        $this->model->forceFill([
            'terms_accepted_at' => now(),
        ]);

        // Check if model not exists and we on backend route then this is
        // user creation flow from admin panel.
        if ( ! $this->model->exists && $request->is('*backend/*') ) {
            $this->model->forceFill([
                'email_verified_at' => now(),
            ]);

            // Generate random pwd if password not provided by a creator from admin panel.
            if ( empty($validated[ 'password' ]) ) {
                $validated[ 'password' ] = ShortCode::get(8);
            }
        }

        if ( ! empty($validated[ 'password' ]) ) {
            $this->model->password = $validated[ 'password' ];
        }

        return $this->model->getDirty();
    }

    /**
     * Run saved hook. Run this hook after full model saving.
     *
     * @param Request $request
     * @param array   $validated
     */
    protected function savedHook(Request $request, array &$validated)
    {
        // Check if model not exists and we on backend route then this is
        // user creation flow from admin panel.
        if ( $this->model->wasRecentlyCreated && $request->is('*backend/*') ) {
            event(new AdminCreatedNewUser($this->model, $validated[ 'password' ]));
        }

        // Cehck if model already exists and password received, then we detect user password changing.
        if ( ! $this->model->wasRecentlyCreated && ! empty($validated[ 'password' ]) ) {
            event(new UserPasswordChanged($this->model, $validated[ 'password' ]));
        }
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

        if ( isset($validated[ 'role_ids' ]) ) {
            $changes[ 'roles' ] = $this->model->roles()->sync($validated[ 'role_ids' ]);
        }

        return $changes;
    }

}
