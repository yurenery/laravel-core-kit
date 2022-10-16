<?php

namespace AttractCores\LaravelCoreKit\Http\Requests;

use AttractCores\LaravelCoreAuth\Http\Requests\CoreRequest;
use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreClasses\CoreFormRequest;

/**
 * Class SimpleCrudRequestCore
 *
 * @version 1.0.0
 * @date    2019-07-28
 * @author  Yure Nery <yurenery@gmail.com>
 */
class SimpleCrudRequestCore extends CoreFormRequest
{

    /**
     * Possible request actions
     *
     * @var array
     */
    protected $actions = [
        'get'  => [
            'methods'    => [ 'GET' ],
            'permission' => CorePermissionContract::CAN_OPERATOR_ACCESS,
        ],
        'post' => [
            'methods'    => [ 'POST' ],
            'permission' => CorePermissionContract::CAN_OPERATOR_ACCESS,
        ],
        'put'  => [
            'methods'    => [ 'PATCH', 'PUT' ],
            'permission' => CorePermissionContract::CAN_OPERATOR_ACCESS,
        ],
    ];

    /**
     * Messages array.
     *
     * @return array
     */
    public function messagesArray()
    {
        return [
            'name.required'      => __('Name field is required.'),
            'name.max'           => __('Name length should be less than 255 chars.'),
            'name.unique'        => __('Name should be unique.'),
            'is_active.required' => __('Is active flag is required.'),
            'is_active.boolean'  => __('Flag value should be boolean.'),
        ];
    }

    /**
     * Return post rules.
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
    public function rulesArray()
    {
        return [
            'is_active' => [ 'required', 'boolean' ],
            'name'      => [ 'required', 'string', 'max:255' ],
        ];
    }

    /**
     * Return put rules.
     *
     * @return array
     */
    public function putAction()
    {
        return $this->rulesArray();
    }

}