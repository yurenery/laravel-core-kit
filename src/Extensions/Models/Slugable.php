<?php

namespace AttractCores\LaravelCoreKit\Extensions\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Trait Slugable
 *
 * @version 1.0.0
 * @date    05/12/2018
 * @author  Yure Nery <yurenery@gmail.com>
 */
trait Slugable
{

    /**
     * Run trait.
     */
    public static function bootSlugable()
    {
        static::saving(function (self $model) {
            $model->applySlug();
        });
    }

    /**
     * Apply slug value.
     *
     * @return $this
     */
    public function applySlug()
    {
        $fieldName = $this->getSlugFieldName();
        $slugFieldBaseName = $this->getSlugBaseFieldName();
        $modelSlug = $this->{$fieldName};

        if (
            ! $modelSlug || ( // slug is empty
                ! $this->isDirty($fieldName) && // slug field not changed
                $this->isDirty($slugFieldBaseName) && // name field changed
                $this->shouldReplaceSlugTogetherWithName() // slug should be driven by name.
            )
        ) {
            $this->attributes[ $fieldName ] = $this->getCompiledSlugValue($slugFieldBaseName);
        }

        return $this;
    }

    /**
     * Return compiled slug value for 'slug' field.
     *
     * @param $fieldName
     *
     * @return string
     */
    protected function getCompiledSlugValue($fieldName)
    {
        return Str::slug($this->{$fieldName});
    }

    /**
     * Return field name for slug value.
     *
     * @return string
     */
    public function getSlugFieldName()
    {
        return 'slug';
    }

    /**
     * Return base field name for slug generation.
     *
     * @return string
     */
    public function getSlugBaseFieldName()
    {
        return 'name';
    }

    /**
     * Determine that model should replace slug value togather with name field value.
     *
     * @return string
     */
    public function shouldReplaceSlugTogetherWithName()
    {
        return true;
    }

    /**
     * Get slug attribute.
     *
     * @param $value
     *
     * @return mixed
     */
    public function getSlugAttribute($value)
    {
        $fieldName = $this->getSlugFieldName();

        return $value ?? $this->getRawOriginal($fieldName);
    }

}
