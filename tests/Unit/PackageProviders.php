<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Test;

/**
 * \Tests\Unit\Playground\Test\PackageProviders
 */
trait PackageProviders
{
    protected function getPackageProviders($app)
    {
        return [
            \Playground\ServiceProvider::class,
            \Playground\Test\ServiceProvider::class,
        ];
    }
}
