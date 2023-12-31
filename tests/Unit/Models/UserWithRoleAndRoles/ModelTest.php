<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Unit\GammaMatrix\Playground\Test\Models\UserWithRoleAndRoles;

use Tests\Unit\GammaMatrix\Playground\Test\TestCase;

/**
 * \Tests\Unit\GammaMatrix\Playground\Test\Models\UserWithRoleAndRoles\ModelTest
 *
 */
class ModelTest extends TestCase
{
    /**
     * @var string
     */
    public const MODEL_CLASS = \GammaMatrix\Playground\Test\Models\UserWithRoleAndRoles::class;

    public function test_getAttributes()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $expected = [
            'name' => '',
            'email' => '',
            'role' => '',
            'roles' => [],
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }

    public function test_hasRole_is_false_without_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = null;

        $this->assertFalse($instance->hasRole($role));
    }

    public function test_hasRole_is_true_with_matching_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = 'user';

        $instance->role = $role;

        $this->assertTrue($instance->hasRole($role));
    }

    public function test_hasRole_is_true_with_matching_secondary_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = 'user';

        $instance->role = $role;

        $instance->roles = [
            'publisher',
        ];

        $this->assertTrue($instance->hasRole($role));
        $this->assertTrue($instance->hasRole('publisher'));
        $this->assertFalse($instance->hasRole('admin'));
        $this->assertFalse($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_admin_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = 'admin';

        $instance->role = $role;

        $instance->roles = [
            'publisher',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_admin_secondary_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = 'publisher';

        $instance->role = $role;

        $instance->roles = [
            'admin',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_wheel_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = 'wheel';

        $instance->role = $role;

        $instance->roles = [
            'publisher',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_wheel_secondary_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = 'publisher';

        $instance->role = $role;

        $instance->roles = [
            'wheel',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_root_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = 'root';

        $instance->role = $role;

        $instance->roles = [
            'publisher',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_false_with_root_secondary_role()
    {
        $mc = static::MODEL_CLASS;

        $instance = new $mc();

        $role = 'publisher';

        $instance->role = $role;

        $instance->roles = [
            'root',
        ];

        $this->assertFalse($instance->isAdmin());
    }
}
