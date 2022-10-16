<?php

namespace AttractCores\LaravelCoreKit\Http\Resources;

use AttractCores\LaravelCoreAuth\Models\User;
use Illuminate\Http\Resources\MissingValue;

/**
 * Class UserResource
 *
 * @property User resource - User resource model
 *
 * @package App\Http\Resources
 */
class UserResource extends CoreJsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $isProfile = $request->is('*profile*');
        $isBackendRequested = isBackend($request);

        return ( [
            'id'                => $this->resource->getKey(),
            'name'              => $this->resource->name,

            'relations' => [
                'roles'       => $this->when($isBackendRequested, function () {
                    return $this->resource->relationLoaded('roles') ?
                        app(RoleResource::class, [ 'resource' => [] ])->collection($this->resource->roles) :
                        new MissingValue;
                }),
                'permissions' => $this->when($isBackendRequested, function () {
                    return $this->resource->relationLoaded('permissions') ?
                        app(PermissionResource::class, [ 'resource' => [] ])->collection($this->resource->permissions) :
                        new MissingValue;
                }),
            ],

            $this->mergeWhen($this->shouldShowUserFields($this->resource, $request, 'id'), function(){
                return [
                    'email'             => $this->resource->email,
                    'email_verified_at' => $this->resource->email_verified_at ?
                        $this->resource->email_verified_at->getPreciseTimestamp(3) : NULL,
                    'terms_accepted_at' => $this->resource->terms_accepted_at ?
                        $this->resource->terms_accepted_at->getPreciseTimestamp(3) : NULL,
                    'created_at'        => $this->resource->created_at ? $this->resource->created_at->getPreciseTimestamp(3) :
                        NULL,
                    'updated_at'        => $this->resource->updated_at ? $this->resource->updated_at->getPreciseTimestamp(3) :
                        NULL,
                ];
            }),
            $this->mergeWhen($this->shouldShowUserFields($this->resource, $request, 'id') && $isProfile, function () {
                return [
                    'permissions' => $this->resource->permissions_codes,
                    'roles_names' => $this->resource->roles_names,
                ];
            }),
            $this->mergeWhen($isBackendRequested &&
                             $this->expandRequested($request, 'permissions'), function () {
                return [
                    'permissions' => $this->resource->permissions_codes,
                ];
            }),
        ] );
    }

}
