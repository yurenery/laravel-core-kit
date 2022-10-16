<?php

namespace AttractCores\LaravelCoreKit\Http\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CoreJsonResource
 *
 * @version 1.0.0
 * @date    2019-09-27
 * @author  Yure Nery <yurenery@gmail.com>
 */
class CoreJsonResource extends JsonResource
{

    /**
     * Create new anonymous resource collection.
     *
     * @param mixed $resource
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return tap(new CoreAnonymousResourceCollection($resource, static::class), function ($collection) {
            if ( property_exists(static::class, 'preserveKeys') ) {
                $collection->preserveKeys = ( new static([]) )->preserveKeys === true;
            }
        });
    }

    /**
     * Resolve the resource to an array.
     *
     * @param \Illuminate\Http\Request|null $request
     *
     * @return array
     */
    public function resolve($request = NULL)
    {
        // Call parent resolver.
        $data = parent::resolve($request);

        // If we have relations array - remove missing.
        if ( isset($data[ 'relations' ]) ) {
            $data[ 'relations' ] = $this->removeMissingValues($data[ 'relations' ]);

            // If relations are empty, remove this key.
            if ( empty($data[ 'relations' ]) ) {
                unset($data[ 'relations' ]);
            }
        }

        return $data;
    }

    /**
     * Determine, that given expand requested.
     *
     * @param Request $request
     * @param         $expand
     *
     * @return bool
     */
    protected function expandRequested(Request $request, $expand)
    {
        $expands = collect(explode(',', $request->input(config('sextant.map.expand'), '')));

        return ! ! $expands->intersect($expand)->count();
    }

    /**
     * Determine that given model should show user fields.
     *
     * @param Model                         $model
     * @param \Illuminate\Http\Request|null $request
     * @param string                        $userIdField
     * @param Model|null                    $user
     *
     * @return bool
     */
    public function shouldShowUserFields(Model $model, ?Request $request, $userIdField = 'user_id', Model $user = NULL)
    {
        $user = $user ?? $request->user();
        $modelUserID = $model->$userIdField;

        return $user && ( $user->getKey() == $modelUserID || isBackend($request) );
    }

}