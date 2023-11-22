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
    /**
     * The Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Get the default Faker instance for a given locale.
     *
     * @param  string|null  $locale
     * @return \Faker\Generator
     */
    protected function faker($locale = null)
    {
        return $this->faker ?? $this->makeFaker($locale);
    }

    /**
     * Create a Faker instance for the given locale.
     *
     * @param  string|null  $locale
     * @return \Faker\Generator
     */
    protected function makeFaker($locale = null)
    {
        return Factory::create($locale ?? Factory::DEFAULT_LOCALE);
    }
}
