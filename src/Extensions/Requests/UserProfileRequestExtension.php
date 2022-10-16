<?php

namespace AttractCores\LaravelCoreKit\Extensions\Requests;

use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreAuth\Rules\ValidatePasswordStrength;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * Trait UserProfileRequestExtension
 *
 * @package AttractCores\LaravelCoreKit\Extensions\Requests
 * Date: 17.12.2020
 * Version: 1.0
 * Author: Yure Nery <yurenery@gmail.com>
 */
trait UserProfileRequestExtension
{

    /**
     * Check if action authorized.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->actions = $this->getTraitExtendedActions();

        return parent::authorize();
    }

    /**
     * Put action rules
     *
     * @return array
     */
    public function putAction()
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

        $rules[ 'password' ] = [ 'nullable', 'sometimes', 'string', 'min:8', 'confirmed', new ValidatePasswordStrength() ];
        $rules[ 'email' ] = [ 'required', 'email', Rule::unique('users', 'email')->ignore($this->user()->getKey()) ];

        return $rules;
    }

    /**
     * Return trait extended actions.
     *
     * @return array[]
     */
    protected function getTraitExtendedActions()
    {
        return [
            'get' => [
                'methods'    => [ 'GET' ],
                'permission' => [ CorePermissionContract::CAN_SIGN_IN, CorePermissionContract::CAN_BACKEND_SIGN_IN ],
            ],
            'put' => [
                'methods'    => [ 'PUT', 'PATCH' ],
                'permission' => [ CorePermissionContract::CAN_SIGN_IN, CorePermissionContract::CAN_BACKEND_SIGN_IN ],
            ],
        ];
    }

    /**
     * Return parent rules.
     *
     * @return array
     */
    public function getParentRules()
    {
        return Arr::except(parent::postAction(), [ 'scopes' ]);
    }

    /**
     * Emulate user update for rest actions.
     */
    protected function prepareForValidation()
    {
        call_user_func($this->getRouteResolver())->setParameter('user', $this->user());
    }

}