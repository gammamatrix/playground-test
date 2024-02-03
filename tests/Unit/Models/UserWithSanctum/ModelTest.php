<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\UserWithSanctum;

use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\UserWithSanctum\ModelTest
 */
class ModelTest extends TestCase
{
    /**
     * @var string
     */
    public const MODEL_CLASS = \Playground\Test\Models\UserWithSanctum::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var \Playground\Test\Models\UserWithSanctum $instance
         */
        $instance = new $mc();

        $expected = [
            'name' => '',
            'email' => '',
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }
}
