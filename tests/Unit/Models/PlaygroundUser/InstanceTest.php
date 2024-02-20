<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\PlaygroundUser;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\PlaygroundUser;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\PlaygroundUser\InstanceTest
 */
#[CoversClass(PlaygroundUser::class)]
class InstanceTest extends TestCase
{
    /**
     * @var class-string<PlaygroundUser>
     */
    public const MODEL_CLASS = PlaygroundUser::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $expected = [
            // 'name' => '',
            // 'email' => '',
            // 'role' => '',
            // 'roles' => [],
            'abilities' => '{}',
            'accounts' => '{}',
            'address' => '{}',
            'meta' => '{}',
            'notes' => '[]',
            'options' => '{}',
            'registration' => '[]',
            'roles' => '[]',
            'permissions' => '[]',
            'privileges' => '[]',
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }

    public function test_hasRole_is_false_without_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $role = null;

        $this->assertFalse($instance->hasRole($role));
    }

    public function test_hasRole_is_true_with_matching_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $role = 'user';

        $instance->role = $role;

        $this->assertTrue($instance->hasRole($role));
    }

    public function test_hasRole_is_true_with_matching_secondary_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
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

    public function test_isAdmin_is_true_with_admin_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $role = 'admin';

        $instance->role = $role;

        $instance->roles = [
            'publisher',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_admin_secondary_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $role = 'publisher';

        $instance->role = $role;

        $instance->roles = [
            'admin',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_wheel_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $role = 'wheel';

        $instance->role = $role;

        $instance->roles = [
            'publisher',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_wheel_secondary_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $role = 'publisher';

        $instance->role = $role;

        $instance->roles = [
            'wheel',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_true_with_root_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $role = 'root';

        $instance->role = $role;

        $instance->roles = [
            'publisher',
        ];

        $this->assertTrue($instance->isAdmin());
    }

    public function test_isAdmin_is_false_with_root_secondary_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = new $mc();

        $role = 'publisher';

        $instance->role = $role;

        $instance->roles = [
            'root',
        ];

        $this->assertFalse($instance->isAdmin());
    }

    public function test_factory_with_make(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var PlaygroundUser $instance
         */
        $instance = $mc::factory()->make();

        $role = 'publisher';

        $instance->role = $role;

        $instance->roles = [
            'root',
        ];

        $this->assertFalse($instance->isAdmin());
    }
}
