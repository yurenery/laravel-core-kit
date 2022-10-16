<?php

namespace AttractCores\LaravelCoreKit\Models;

use Amondar\Sextant\Models\SextantModel;
use AttractCores\LaravelCoreKit\Extensions\Models\ActiveScope;
use Carbon\Carbon;

/**
 * Class SimpleCrudModelCore
 *
 * @property string  name                    - name of the model.
 * @property boolean is_active               - check is model active.
 * @property Carbon  created_at              - Created at of the model.
 * @property Carbon  updated_at              - Updated at of the model.
 *
 *
 * @version 1.0.0
 * @date    2019-08-05
 * @author  Yure Nery <yurenery@gmail.com>
 */
class SimpleCrudModelCore extends SextantModel
{

    use ActiveScope;

    /**
     * Model fillables.
     *
     * @var array
     */
    protected $fillable = [ 'name', 'is_active' ];

    /**
     * Cast some data.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

}
