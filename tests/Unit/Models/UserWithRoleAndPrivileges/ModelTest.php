<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\UserWithRoleAndPrivileges;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\UserWithRoleAndPrivileges;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\UserWithRoleAndPrivileges\ModelTest
 */
#[CoversClass(UserWithRoleAndPrivileges::class)]
class ModelTest extends TestCase
{
    /**
     * @var class-string<UserWithRoleAndPrivileges>
     */
    public const MODEL_CLASS = UserWithRoleAndPrivileges::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRoleAndPrivileges $instance
         */
        $instance = new $mc();

        $expected = [
            'name' => '',
            'email' => '',
            'role' => '',
            'roles' => [],
            'privileges' => [],
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }

    public function test_hasPrivilege_is_false_without_privilege(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRoleAndPrivileges $instance
         */
        $instance = new $mc();

        $privilege = null;

        $this->assertFalse($instance->hasPrivilege($privilege));
    }

    public function test_hasPrivilege_is_false_with_wrong_privilege(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRoleAndPrivileges $instance
         */
        $instance = new $mc();

        $privilege = 'duck';

        $instance->privileges = [
            'goose',
        ];

        $this->assertFalse($instance->hasPrivilege($privilege));
    }

    public function test_hasPrivilege_is_true_with_correct_privilege(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRoleAndPrivileges $instance
         */
        $instance = new $mc();

        $privilege = 'duck';

        $instance->privileges = [
            'goose',
        ];

        $this->assertFalse($instance->hasPrivilege($privilege));

        $privilege = 'goose';
        $this->assertTrue($instance->hasPrivilege($privilege));
    }

    public function test_factory_with_make(): void
    {
        // $mc = $this->modelClass;
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRoleAndPrivileges $instance
         */
        $instance = $mc::factory()->make();

        $this->assertInstanceOf($mc, $instance);
    }
}
