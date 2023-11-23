<?php
/**
 * Laravel
 */

namespace GammaMatrix\Playground\Test;

use Faker\Factory;
use Faker\Generator;

/**
 * \GammaMatrix\Playground\Test\WithFaker
 *
 */
trait WithFaker
{
    protected $faker;

    protected function faker(string $locale = null)
    {
        if (!$this->faker) {
            $this->faker = Factory::create($locale ?? Factory::DEFAULT_LOCALE);
        }
        return $this->faker;
    }
}
