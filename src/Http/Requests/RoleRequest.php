<?php

namespace AttractCores\LaravelCoreKit\Http\Requests;

use AttractCores\LaravelCoreAuth\Http\Requests\CoreRequest;
use AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract;
use AttractCores\LaravelCoreAuth\Resolvers\CoreRoleContract;
use AttractCores\LaravelCoreClasses\CoreFormRequest;
use AttractCores\LaravelCoreKit\Rules\ValidSlugRule;
use Illuminate\Validation\Rule;

/**
 * Class RoleRequest
 *
 * @package AttractCores\LaravelCoreKit\Http\Requests
 */
class RoleRequest extends CoreFormRequest
{

    /**
     * Possible actions
     *
     * @var array
     */
    protected $actions = [
        'get'     => [
            'methods'    => [ 'GET' ],
            'permission' => CorePermissionContract::CAN_OPERATOR_ACCESS,
        ],
        'post'    => [
            'methods'    => [ 'POST' ],
            'permission' => CorePermissionContract::CAN_ADMIN_ACCESS,
        ],
        'put'     => [
            'methods'    => [ 'PUT', 'PATCH' ],
            'permission' => CorePermissionContract::CAN_ADMIN_ACCESS,
        ],
        'destroy' => [
            'methods'    => [ 'DELETE' ],
            'permission' => CorePermissionContract::CAN_ADMIN_ACCESS,
        ],
    ];

    /**
     * Check if action authorized.
     *
     * @return bool
     */
    public function authorize()
    {
        $result = parent::authorize();

        if ( $this->actionName == 'destroy' ) {
            $role = $this->routeModel('role', CoreRoleContract::class);

            return ! $role->isProtected();
        }

        return $result;
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
    public function rulesArray()
    {
        return [
            'slug'             => [ 'bail', 'required', 'string', new ValidSlugRule(), Rule::unique('roles', 'slug') ],
            'name_en'          => [ 'required', 'string' ],
            'permission_ids'   => [ 'required', 'array' ],
            'permission_ids.*' => [ 'required', Rule::exists('permissions', 'id') ],
        ];
    }

    /**
     * Put action rules
     *
     * @return array
     */
    public function putAction()
    {
        $rules = $this->rulesArray();
        $model = $this->routeModel('role', CoreRoleContract::class);

        if ( $model->isProtected() ) {
            $this->merge([ 'slug' => $model->slug ]);
        } else {
            $rules[ 'slug' ] = [
                'bail', 'required', 'string', new ValidSlugRule(), Rule::unique('roles', 'slug')
                                                                       ->ignoreModel($model),
            ];
        }

        return $rules;
    }

    /**
     * Messages array.
     *
     * @return array
     */
    public function messagesArray()
    {
        return [
            'slug.required'           => __('Slug field is required.'),
            'slug.unique'             => __('Slug should be unique.'),
            'name_en.required'        => __('Role name is required.'),
            'permission_ids.required' => __("You can't create a role without permissions."),
            'permission_ids.*.exists' => __('Given permission is not exists in our db.'),
        ];
    }

}
