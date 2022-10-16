<?php

namespace AttractCores\LaravelCoreKit\Http\Resources;

use AttractCores\LaravelCoreKit\Models\Permission;

/**
 * Class PermissionResource
 *
 * @property \AttractCores\LaravelCoreAuth\Resolvers\CorePermissionContract resource - Resource model.
 *
 * @package App\Http\Resources
 */
class PermissionResource extends CoreJsonResource
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
        return [
            'id'         => $this->resource->getKey(),
            'slug'       => $this->resource->slug,
            'name'       => $this->resource->name_en,
            'name_en'    => $this->resource->name_en,
            'created_at' => $this->resource->created_at ? $this->resource->created_at->getPreciseTimestamp(3) : NULL,
            'updated_at' => $this->resource->updated_at ? $this->resource->updated_at->getPreciseTimestamp(3) : NULL,
            'relations'  => [
                'roles' => $this->whenLoaded('roles', function () {
                    return RoleResource::collection($this->resource->roles);
                }),
            ],
        ];
    }

}
