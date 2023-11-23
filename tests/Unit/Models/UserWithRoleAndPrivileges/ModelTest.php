<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Unit\GammaMatrix\Playground\Test\Models\UserWithRoleAndPrivileges;

use Tests\Unit\GammaMatrix\Playground\Test\TestCase;

/**
 * \Tests\Unit\GammaMatrix\Playground\Test\Models\UserWithRoleAndPrivileges\ModelTest
 *
 */
class ModelTest extends TestCase
{
    /**
     * @var string
     */
    public const MODEL_CLASS = \GammaMatrix\Playground\Test\Models\UserWithRoleAndPrivileges::class;

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

    public function test_hasPrivilege_is_false_without_privilege()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $privilege = null;

        $this->assertFalse($instance->hasPrivilege($privilege));
    }

    public function test_hasPrivilege_is_false_with_wrong_privilege()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $privilege = 'duck';

        $instance->privileges = [
            'goose',
        ];

        $this->assertFalse($instance->hasPrivilege($privilege));
    }

    public function test_hasPrivilege_is_true_with_correct_privilege()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $privilege = 'duck';

        $instance->privileges = [
            'goose',
        ];

        $this->assertFalse($instance->hasPrivilege($privilege));

        $privilege = 'goose';
        $this->assertTrue($instance->hasPrivilege($privilege));
    }
}
