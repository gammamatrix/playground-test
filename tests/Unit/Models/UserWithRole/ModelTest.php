<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\UserWithRole;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\Concerns\UserPrivileges;
use Playground\Test\Models\UserWithRole;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\UserWithRole\ModelTest
 */
#[CoversClass(UserWithRole::class)]
#[CoversClass(UserPrivileges::class)]
class ModelTest extends TestCase
{
    /**
     * @var class-string<UserWithRole>
     */
    public const MODEL_CLASS = UserWithRole::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRole $instance
         */
        $instance = new $mc();

        $expected = [
            'name' => '',
            'email' => '',
            'role' => '',
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }

    public function test_hasRole_is_false_without_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRole $instance
         */
        $instance = new $mc();

        $role = null;

        $this->assertFalse($instance->hasRole($role));
    }

    public function test_hasRole_is_true_with_matching_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRole $instance
         */
        $instance = new $mc();

        $role = 'user';

        $instance->role = $role;

        $this->assertTrue($instance->hasRole($role));
    }

    public function test_hasRole_is_true_with_array_of_roles(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRole $instance
         */
        $instance = new $mc();

        $role = 'user';

        $instance->role = $role;

        $this->assertTrue($instance->hasRole($role));
        $this->assertTrue($instance->hasRole(['publisher', 'user', 'admin']));
        $this->assertFalse($instance->hasRole('admin'));
        $this->assertFalse($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_admin_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRole $instance
         */
        $instance = new $mc();

        $role = 'admin';

        $instance->role = $role;

        $this->assertTrue($instance->isAdmin());
    }
}
