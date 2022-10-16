<?php

namespace AttractCores\LaravelCoreKit\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class CoreResourceCollection
 *
 * @version 1.0.0
 * @date    2019-09-27
 * @author  Yure Nery <yurenery@gmail.com>
 */
class CoreResourceCollection extends ResourceCollection
{

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
        if ( $this->checkThatDataKeysIsSimple($data) ) {
            $data = $this->simplifyCollectionRelations($data);
        }

        return $data;
    }

    /**
     * Check if given array is simple array, not string keyed.
     *
     * @param array $data
     *
     * @return bool
     */
    protected function checkThatDataKeysIsSimple(array $data)
    {
        foreach ( array_keys($data) as $key ) {
            if ( is_string($key) ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Remove missing values from relations arrays and remove relations array if it's empty.
     *
     * @param $data
     *
     * @return mixed
     */
    protected function simplifyCollectionRelations($data)
    {
        foreach ( $data as &$datum ) {
            if ( isset($datum[ 'relations' ]) ) {
                $datum[ 'relations' ] = $this->removeMissingValues($datum[ 'relations' ]);

                // If relations are empty, remove this key.
                if ( empty($datum[ 'relations' ]) ) {
                    unset($datum[ 'relations' ]);
                }
            }
        }

        return $data;
    }

}