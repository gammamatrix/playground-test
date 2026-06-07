<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Test\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert;

trait Factories
{
    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<string, mixed>  $meta
     * @return Factory<Model>
     */
    protected function getFactory(
        string $modelClass,
        array $meta = []
    ): Factory {

        Assert::assertTrue(
            class_exists($modelClass),
            __('playground-test:model.404', [
                'model' => $modelClass,
            ])
        );

        $state = array_key_exists('state', $meta) && is_string($meta['state']) ? $meta['state'] : '';
        $options = array_key_exists('options', $meta) && is_array($meta['options']) ? $meta['options'] : [];

        Assert::assertTrue(
            is_callable([$modelClass, 'factory']),
            __('playground-test:model.factory.404', [
                'state' => $state,
                'model' => $modelClass,
            ])
        );

        $factory = $modelClass::factory();

        if ($state === '') {
            return $factory;
        }

        Assert::assertTrue(
            method_exists($factory, $state),
            __('playground-test:model.factory.state.invalid', [
                'state' => $state,
                'model' => $modelClass,
            ])
        );

        $factoryState = $factory->{$state}(...$options);

        Assert::assertInstanceOf($factory, $factoryState);

        return $factoryState;
    }
}
