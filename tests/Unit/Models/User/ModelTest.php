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
}
