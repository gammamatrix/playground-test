<?php
/**
 * Laravel
 */
namespace Playground\Test;

use Faker\Factory;
use Faker\Generator;

/**
 * \Playground\Test\WithFaker
 */
trait WithFaker
{
    protected ?Generator $faker;

    protected function faker(string $locale = null): Generator
    {
        if (! $this->faker) {
            $this->faker = Factory::create($locale ?? Factory::DEFAULT_LOCALE);
        }

        return $this->faker;
    }
}
