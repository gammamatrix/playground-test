<?php
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
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }
}
