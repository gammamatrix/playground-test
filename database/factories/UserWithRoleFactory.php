<?php
/**
 * Playground
 */
namespace Database\Factories\Playground\Test\Models;

use Playground\Test\Models\UserWithRole;

/**
 * \Database\Factories\Playground\Test\Models\UserWithRoleFactory
 */
class UserWithRoleFactory extends AbstractUserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<UserWithRole>
     */
    protected $model = UserWithRole::class;

    /**
     * Indicate that the user has the admin role.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user has the manager role.
     */
    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'manager',
        ]);
    }

    /**
     * Indicate that the user has the wheel role.
     */
    public function wheel(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'wheel',
        ]);
    }

    /**
     * Indicate that the user has the root role.
     */
    public function root(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'root',
        ]);
    }
}
