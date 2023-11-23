<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Unit\GammaMatrix\Playground\Test\Models\User;

use Tests\Unit\GammaMatrix\Playground\Test\TestCase;

/**
 * \Tests\Unit\GammaMatrix\Playground\Test\Models\User\ModelTest
 *
 */
class ModelTest extends TestCase
{
    /**
     * @var string
     */
    public const MODEL_CLASS = \GammaMatrix\Playground\Test\Models\User::class;

    public function test_getAttributes()
    {
        $mc = static::MODEL_CLASS;

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
