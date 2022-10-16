<?php

namespace App\Repositories;

use AttractCores\LaravelCoreClasses\CoreRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CoreRepository
 *
 * @package App\Repositories
 */
abstract class CoreSimpleCrudRepository extends CoreRepository
{

    /**
     * Store/update model.
     *
     * @param NULL|FormRequest $request
     * @param array|null       $validated
     *
     * @return Model
     */
    public function storeOrUpdate(?FormRequest $request, array $validated = NULL) : Model
    {
        $validated = $validated ?? $request->validated();
        $request = $request ?? request();

        // Fill model data.
        $this->model
            ->fill($validated);

        // Call saving hook;
        $this->savingHook($request, $validated);

        // Save model.
        $this->model->save();

        // Run model relations set up.
        $this->setModelRelations($request, $validated);

        // Call saved hook.
        $this->savedHook($request, $validated);

        return $this->model->load($this->withRelations());
    }

    /**
     * @return array
     */
    protected function withRelations():array
    {
        return [];
    }

}
