<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\AppPlaygroundUser;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\AppPlaygroundUser;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Test\Models\AppPlaygroundUser\InstanceTest
 */
#[CoversClass(AppPlaygroundUser::class)]
class InstanceTest extends TestCase
{
    /**
     * @var class-string<AppPlaygroundUser>
     */
    public const MODEL_CLASS = AppPlaygroundUser::class;

    public function test_getAttributes(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var AppPlaygroundUser $instance
         */
        $instance = new $mc();

        $expected = [
            'created_by_id' => null,
            'modified_by_id' => null,
            'user_type' => null,
            'created_at' => null,
            'updated_at' => null,
            'deleted_at' => null,
            'banned_at' => null,
            'suspended_at' => null,
            'gids' => 0,
            'po' => 0,
            'pg' => 0,
            'pw' => 0,
            'status' => 0,
            'rank' => 0,
            'size' => 0,
            'active' => true,
            'banned' => false,
            'flagged' => false,
            'internal' => false,
            'locked' => false,
            'problem' => false,
            'suspended' => false,
            'unknown' => false,
            'name' => '',
            'email' => '',
            'password' => '',
            'phone' => null,
            'locale' => '',
            'timezone' => '',
            'label' => '',
            'title' => '',
            'byline' => '',
            'slug' => null,
            'url' => '',
            'description' => '',
            'introduction' => '',
            'content' => null,
            'summary' => null,
            'icon' => '',
            'image' => '',
            'avatar' => '',
            // JSON
            'ui' => '{}',
            // Abilities are shared with SPAs
            'abilities' => '[]',
            'accounts' => '{}',
            'address' => '{}',
            'contact' => '{}',
            'meta' => '{}',
            'notes' => '[]',
            'options' => '{}',
            'registration' => '{}',
            'roles' => '[]',
            'permissions' => '[]',
            'privileges' => '[]',
            'sources' => '[]',
        ];

        $attributes = $instance->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }

    public function test_hasRole_is_false_without_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var AppPlaygroundUser $instance
         */
        $instance = new $mc();

        $role = null;

        $this->assertFalse($instance->hasRole($role));
    }

    public function test_hasRole_is_true_with_matching_role(): void
    {
        $mc = static::MODEL_CLASS;

        /**
         * @var AppPlaygroundUser $instance
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
         * @var AppPlaygroundUser $instance
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
         * @var AppPlaygroundUser $instance
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
         * @var AppPlaygroundUser $instance
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
         * @var AppPlaygroundUser $instance
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
         * @var AppPlaygroundUser $instance
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
         * @var AppPlaygroundUser $instance
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
         * @var AppPlaygroundUser $instance
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
         * @var AppPlaygroundUser $instance
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
