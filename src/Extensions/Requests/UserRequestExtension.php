<?php

namespace AttractCores\LaravelCoreKit\Extensions\Requests;

use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreAuth\Resolvers\CoreUserContract;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * Trait UserRequestExtension
 *
 * @package AttractCores\LaravelCoreKit\Extensions\Requests
 * Date: 17.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
trait UserRequestExtension
{

    /**
     * Check if action authorized.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->actions = $this->getTraitExtendedActions();
        $result = parent::authorize();
        $currentUser = $this->user();

        if ( $this->actionName == 'destroy' ) {
            $userOnAction = $this->routeModel('user', CoreUserContract::class);

            return ! $userOnAction->isProtected() && $userOnAction->canBeChangedByGivenUser($currentUser);
        } elseif ( $this->actionName == 'put' ) {
            $userOnAction = $this->routeModel('user', CoreUserContract::class);

            return $userOnAction->canBeChangedByGivenUser($currentUser);
        }

        return $result;
    }

    /**
     * Messages array.
     *
     * @return array
     */
    public function messagesArray()
    {
        $messages = parent::messagesArray();

        return array_merge($messages, [
            'role_ids.required' => __('You should specify at least one role.'),
            'role_ids.*.exists' => __('This role does not exist, or you are trying to use deprecated role for your access.'),
        ]);
    }

    /**
     * Return trait extended actions.
     *
     * @return array[]
     */
    protected function getTraitExtendedActions()
    {
        return [
            'get'     => [
                'methods'    => [ 'GET' ],
                'permission' => CorePermissionContract::CAN_OPERATOR_ACCESS,
            ],
            'post'    => [
                'methods'    => [ 'POST' ],
                'permission' => CorePermissionContract::CAN_OPERATOR_ACCESS,
            ],
            'put'     => [
                'methods'    => [ 'PUT', 'PATCH' ],
                'permission' => CorePermissionContract::CAN_OPERATOR_ACCESS,
            ],
            'destroy' => [
                'methods'    => [ 'DELETE' ],
                'permission' => CorePermissionContract::CAN_OPERATOR_ACCESS,
            ],
        ];
    }

    /**
     * Post action rules
     *
     * @return array
     */
    public function postAction()
    {
        return $this->rulesArray();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function extendedRulesArray()
    {
        $rules = $this->getParentRules();

        $rules[ 'role_ids' ] = [ 'required', 'array' ];
        $rules[ 'role_ids.*' ] = [
            'required',
            $this->user()->isOperator() ?
                // operator possible roles
                Rule::exists('roles', 'id')->whereNotIn('slug', [
                    CoreRoleContract::CAN_BACKEND_SIGN_IN, CoreRoleContract::CAN_ADMIN, CoreRoleContract::CAN_OPERATOR,
                ]) : // admin possible roles.
                Rule::exists('roles', 'id'),
        ];

        return $rules;
    }

    /**
     * Return parent rules.
     *
     * @return array
     */
    public function getParentRules()
    {
        return Arr::except(parent::postAction(), [ 'firebase_token', 'terms_accepted', 'scopes' ]);
    }

    /**
     * Put action rules
     *
     * @return array
     */
    public function putAction()
    {
        $rules = $this->rulesArray();
        $model = $this->routeModel('user', CoreUserContract::class);

        $rules[ 'email' ] = [ 'required', 'string', 'email', Rule::unique('users', 'email')->ignoreModel($model), ];
        $rules[ 'password' ] = [ 'nullable', 'sometimes', 'string', 'min:8', 'confirmed' ];

        return $rules;
    }

}