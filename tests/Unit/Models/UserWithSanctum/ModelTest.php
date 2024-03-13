<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\UserWithSanctum;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\UserWithSanctum;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\UserWithSanctum\ModelTest
 */
#[CoversClass(UserWithSanctum::class)]
class ModelTest extends TestCase
{
    /**
     * @var class-string<UserWithSanctum>
     */
    public const MODEL_CLASS = UserWithSanctum::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithSanctum $instance
         */
        $instance = new $mc();

        $expected = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }

    public function test_factory_with_make(): void
    {
        // $mc = $this->modelClass;
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithSanctum $instance
         */
        $instance = $mc::factory()->make();

        $this->assertInstanceOf($mc, $instance);
    }
}
