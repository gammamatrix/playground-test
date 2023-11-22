<?php
/**
 * GammaMatrix
 */

namespace Database\Factories\GammaMatrix\Playground\Test\Models;

use GammaMatrix\Playground\Test\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * \Database\Factories\GammaMatrix\Playground\Test\Models\AbstractUserFactory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\GammaMatrix\Playground\Test\Models\AbstractUser>
 */
abstract class AbstractUserFactory extends Factory
{
    use WithFaker;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \GammaMatrix\Playground\Test\Models\AbstractUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker()->name(),
            'email' => $this->faker()->unique()->safeEmail(),
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make(config('playground-test.password')),
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
