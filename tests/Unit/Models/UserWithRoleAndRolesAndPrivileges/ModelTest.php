<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\UserWithRoleAndRolesAndPrivileges;

use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\UserWithRoleAndRolesAndPrivileges\ModelTest
 */
class ModelTest extends TestCase
{
    /**
     * @var string
     */
    public const MODEL_CLASS = \Playground\Test\Models\UserWithRoleAndRolesAndPrivileges::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var \Playground\Test\Models\UserWithRoleAndRolesAndPrivileges $instance
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
