<?php
/**
 * Playground
 */
namespace Database\Factories\Playground\Test\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Playground\Test\Models\UserWithSanctum;

/**
 * \Database\Factories\Playground\Test\Models\UserWithSanctumFactory
 */
class UserWithSanctumFactory extends AbstractUserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<UserWithSanctum>
     */
    protected $model = UserWithSanctum::class;

    /**
     * Set the user up as an admin user.
     */
    public function admin(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'admin@example.com',
        ]);
    }

    /**
     * Set the user up as a guest user.
     */
    public function guest(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'guest@example.com',
        ]);
    }

    /**
     * Set the user up as a manager user.
     */
    public function manager(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'manager@example.com',
        ]);
    }
}
