<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\User;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\User;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\User\ModelTest
 */
#[CoversClass(User::class)]
class ModelTest extends TestCase
{
    /**
     * @var class-string<User>
     */
    public const MODEL_CLASS = User::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var User $instance
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

    public function test_factory_with_make(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var User $instance
         */
        $instance = $mc::factory()->make();

        $this->assertInstanceOf($mc, $instance);
    }
}
