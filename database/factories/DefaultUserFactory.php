<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Database\Factories\Playground\Test\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Playground\Test\Models\DefaultUser;

/**
 * \Database\Factories\Playground\Test\Models\DefaultUserFactory
 */
class DefaultUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<DefaultUser>
     */
    protected $model = DefaultUser::class;

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
            'password' => static::$password,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

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
     * Set the user up as an admin user.
     */
    public function admin(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'admin@example.dev',
        ]);
    }

    /**
     * Set the user up as a guest user.
     */
    public function guest(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'guest@example.dev',
        ]);
    }

    /**
     * Set the user up as a manager user.
     */
    public function manager(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'manager@example.dev',
        ]);
    }

    /**
     * Indicate that the user has the root role.
     */
    public function root(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'root@example.dev',
        ]);
    }

    /**
     * Indicate that the user has the wheel role.
     */
    public function wheel(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email' => 'wheel@example.dev',
        ]);
    }
}
