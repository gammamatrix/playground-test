<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Test\Feature\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert;
use Playground\Test\Feature\Models\ModelCase;

/**
 * @mixin ModelCase
 */
trait HasMany
{
    /**
     * Verify a HasMany model relationship.
     *
     * @param array{
     *      key: string,
     *      modelClass: class-string<Model>,
     *      options?: array<string, mixed>,
     *      rule?: "create"|"first",
     *      state?: string,
     *      stateHasMany?: string,
     *      optionsHasMany?: array<string, mixed>,
     *      count?: int
     *  } $meta
     */
    public function verifyRelationshipHasMany(
        string $accessor,
        array $meta = [
            'key' => '',
            'modelClass' => Model::class,
        ]
    ): void {

        $outModelClass = $this->getModelClass();

        $key = array_key_exists('key', $meta) && is_string($meta['key']) ? $meta['key'] : '';
        $modelClass = array_key_exists('modelClass', $meta) && is_string($meta['modelClass']) ? $meta['modelClass'] : '';
        $rule = array_key_exists('rule', $meta) && is_string($meta['rule']) ? $meta['rule'] : '';
        $count = array_key_exists('count', $meta) && is_numeric($meta['count']) && $meta['count'] > 0 ? intval($meta['count']) : 3;
        $state = array_key_exists('state', $meta) && is_string($meta['state']) ? $meta['state'] : '';
        $stateHasMany = array_key_exists('stateHasMany', $meta) && is_string($meta['stateHasMany']) ? $meta['stateHasMany'] : '';
        $options = array_key_exists('options', $meta) && is_array($meta['options']) ? $meta['options'] : [];
        $optionsHasMany = array_key_exists('options', $meta) && is_array($meta['options']) ? $meta['options'] : [];

        if ($this->debugModels) {
            dump(__('playground-test::model.debug.feature.has.many', ['accessor' => $accessor, 'model' => $outModelClass]));
        }

        Assert::assertNotEmpty($accessor, sprintf(
            'Expecting the HasMany accessor [%1$s] to be provided in %2$s::$hasMany[%1$s]',
            $accessor,
            get_called_class()
        ));

        Assert::assertNotEmpty($key, sprintf(
            'Expecting the HasMany accessor [%1$s] to have a key in %2$s::$hasMany[%1$s][key]',
            $accessor,
            get_called_class()
        ));

        Assert::assertTrue(class_exists($modelClass), sprintf(
            'Expecting the HasMany accessor [%1$s] to have a modelClass in %2$s::$hasMany[%1$s][modelClass]',
            $accessor,
            get_called_class()
        ));

        if ($rule === 'first') {
            /**
             * @var Model $model
             */
            $model = $this->getFactory($outModelClass, [
                'state' => $state,
                'options' => $options,
            ])->create();
        } else {
            /**
             * @var Model $model
             */
            $model = $this->getFactory($outModelClass, [
                'state' => $state,
                'options' => $options,
            ])->has(
                $this->getFactory($modelClass, [
                    'state' => $stateHasMany,
                    'options' => $optionsHasMany,
                ])->count($count),
                $accessor
            )->create();
        }

        Assert::assertInstanceOf(
            Model::class,
            $model
        );

        $callback = [$model, $accessor];
        Assert::assertIsCallable($callback, __('playground-test:model.accessor.404', [
            'model' => $modelClass,
            'accessor' => $accessor,
        ]));

        $relationship = call_user_func_array($callback, []);
        Assert::assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $relationship
        );

        foreach ($relationship->get() as $m) {
            Assert::assertInstanceOf($modelClass, $m, sprintf(
                'Expecting the created HasMany model for the accessor [%1$s] to be an instance of %2$s - found: %3$s - %4$s',
                $accessor,
                $modelClass,
                get_class($m),
                get_called_class()
            ));
            Assert::assertSame(
                $m->getAttributeValue($key),
                $model->getAttributeValue('id'),
                sprintf(
                    'Expecting the created HasMany model for the accessor [%1$s] to have model->id === m->%2$s - modelClass: %3$s - %4$s - %5$s',
                    $accessor,
                    $key,
                    $modelClass,
                    get_class($m),
                    get_called_class()
                )
            );
        }

        if ($this->debugModels) {
            dump(__('playground-test::model.debug.feature.has.many.success', ['accessor' => $accessor, 'model' => $outModelClass]));
        }
    }
}
