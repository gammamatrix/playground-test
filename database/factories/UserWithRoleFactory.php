<?php
/**
 * GammaMatrix
 */

namespace Database\Factories\GammaMatrix\Playground\Test\Models;

/**
 * \Database\Factories\GammaMatrix\Playground\Test\Models\UserWithRoleFactory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\GammaMatrix\Playground\Test\Models\UserWithRole>
 */
class UserWithRoleFactory extends AbstractUserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \GammaMatrix\Playground\Test\Models\UserWithRole::class;

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
