<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Database\Factories\Playground\Test\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Playground\Test\Models\UserWithRoleAndRolesAndPrivileges;

/**
 * \Database\Factories\Playground\Test\Models\UserWithRoleAndRolesAndPrivilegesFactory
 */
class UserWithRoleAndRolesAndPrivilegesFactory extends AbstractUserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<UserWithRoleAndRolesAndPrivileges>
     */
    protected $model = UserWithRoleAndRolesAndPrivileges::class;

    /**
     * Indicate that the user has the admin role.
     */
    public function admin(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user has the guest role.
     */
    public function guest(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'guest',
        ]);
    }

    /**
     * Indicate that the user has the manager role.
     */
    public function manager(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'manager',
        ]);
    }

    /**
     * Indicate that the user has the root role.
     */
    public function root(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'root',
        ]);
    }

    /**
     * Indicate that the user has the wheel role.
     */
    public function wheel(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'wheel',
        ]);
    }
}
