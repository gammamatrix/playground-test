<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\UserWithRoleAndRolesAndPrivileges;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\Traits\UserPrivileges;
use Playground\Test\Models\UserWithRoleAndRolesAndPrivileges;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\UserWithRoleAndRolesAndPrivileges\ModelTest
 */
#[CoversClass(UserWithRoleAndRolesAndPrivileges::class)]
#[CoversClass(UserPrivileges::class)]
class ModelTest extends TestCase
{
    /**
     * @var class-string<UserWithRoleAndRolesAndPrivileges>
     */
    public const MODEL_CLASS = UserWithRoleAndRolesAndPrivileges::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var UserWithRoleAndRolesAndPrivileges $instance
         */
        $instance = new $mc();

        $expected = [
            'name' => '',
            'email' => '',
            'role' => '',
            'roles' => [],
            'privilieges' => [],
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }
}
