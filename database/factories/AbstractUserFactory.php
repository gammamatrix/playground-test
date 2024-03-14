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
use Playground\Test\Models\AbstractUser;

/**
 * \Database\Factories\Playground\Test\Models\AbstractUserFactory
 */
abstract class AbstractUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<AbstractUser>
     */
    protected $model = AbstractUser::class;

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

            if (! empty($password)) {
                static::$password = $password;
            }
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
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
