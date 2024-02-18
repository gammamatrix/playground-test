<?php
/**
 * Playground
 */
namespace Database\Factories\Playground\Test\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Playground\Test\Models\PlaygroundUserWithSanctum;

/**
 * \Database\Factories\Playground\Test\Models\PlaygroundUserWithSanctumFactory
 */
class PlaygroundUserWithSanctumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<PlaygroundUserWithSanctum>
     */
    protected $model = PlaygroundUserWithSanctum::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $password = config('playground-test.password', 'password');

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => Hash::make(
                $password && is_string($password) ? $password : md5(date('c'))
            ),
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
