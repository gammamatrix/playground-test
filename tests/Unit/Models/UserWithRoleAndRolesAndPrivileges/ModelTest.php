<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Unit\GammaMatrix\Playground\Test\Models\UserWithRoleAndRolesAndPrivileges;

use Tests\Unit\GammaMatrix\Playground\Test\TestCase;

/**
 * \Tests\Unit\GammaMatrix\Playground\Test\Models\UserWithRoleAndRolesAndPrivileges\ModelTest
 *
 */
class ModelTest extends TestCase
{
    /**
     * @var string
     */
    public const MODEL_CLASS = \GammaMatrix\Playground\Test\Models\UserWithRoleAndRolesAndPrivileges::class;

    public function test_getAttributes()
    {
        $mc = static::MODEL_CLASS;

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
