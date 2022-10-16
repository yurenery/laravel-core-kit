<?php

namespace AttractCores\LaravelCoreKit\Extensions\Models;

/**
 * Trait ActiveScope
 *
 * @version 1.0.0
 * @date    2019-09-10
 * @author  Yure Nery <yurenery@gmail.com>
 */
trait ActiveScope
{

    /**
     * Return only active data.
     *
     * @param $query
     */
    public function scopeActive($query)
    {
        $query->where($this->getTable() . '.is_active', true);
    }

    /**
     * Return only in-active data.
     *
     * @param $query
     */
    public function scopeInActive($query)
    {
        $query->where($this->getTable() . '.is_active', false);
    }

}