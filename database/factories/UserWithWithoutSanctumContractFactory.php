<?php

/**
 * Playground
 */

declare(strict_types=1);
namespace Database\Factories\Playground\Test\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Playground\Test\Models\UserWithWithoutSanctumContract;

/**
 * \Database\Factories\Playground\Test\Models\UserWithWithoutSanctumContractFactory
 */
class UserWithWithoutSanctumContractFactory extends AbstractUserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<UserWithWithoutSanctumContract>
     */
    protected $model = UserWithWithoutSanctumContract::class;

    /**
     * Set the user up as an admin user.
     *
     * @return Factory<UserWithWithoutSanctumContract>
     */
    public function admin(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'admin@example.dev',
        ]);
    }

    /**
     * Set the user up as a guest user.
     *
     * @return Factory<UserWithWithoutSanctumContract>
     */
    public function guest(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'guest@example.dev',
        ]);
    }

    /**
     * Set the user up as a manager user.
     *
     * @return Factory<UserWithWithoutSanctumContract>
     */
    public function manager(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'manager@example.dev',
        ]);
    }

    /**
     * Indicate that the user has the root role.
     *
     * @return Factory<UserWithWithoutSanctumContract>
     */
    public function root(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'root@example.dev',
        ]);
    }

    /**
     * Indicate that the user has the wheel role.
     *
     * @return Factory<UserWithWithoutSanctumContract>
     */
    public function wheel(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'wheel@example.dev',
        ]);
    }
}
