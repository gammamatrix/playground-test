<?php
/**
 * Playground
 */
namespace Database\Factories\Playground\Test\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * \Database\Factories\Playground\Test\Models\AbstractPlaygroundUserFactory
 */
abstract class AbstractPlaygroundUserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (empty(static::$password)) {
            $password = config('auth.testing.password');
            $test_password_hashed = config('auth.testing.hashed');

            if (empty($password) || ! is_string($password)) {
                $password = md5(Carbon::now()->format('c'));
                $test_password_hashed = false;
            }

            if (! $test_password_hashed) {
                $password = Hash::make($password);
            }

            static::$password = $password;
        }

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'role' => 'user',
            'password' => static::$password,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

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
     * Indicate that the user has the manager role.
     */
    public function manager(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'manager',
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

    /**
     * Indicate that the user has the root role.
     */
    public function root(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'root',
        ]);
    }
}
