<?php

namespace AttractCores\LaravelCoreKit\Http\Resources;

/**
 * Class CoreAnonymousResourceCollection
 *
 * @version 1.0.0
 * @date    2019-09-27
 * @author  Yure Nery <yurenery@gmail.com>
 */
class CoreAnonymousResourceCollection extends CoreResourceCollection
{

    /**
     * The name of the resource being collected.
     *
     * @var string
     */
    public $collects;

    /**
     * Create a new anonymous resource collection.
     *
     * @param mixed  $resource
     * @param string $collects
     *
     * @return void
     */
    public function __construct($resource, $collects)
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }

}