<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Playground\Test\OrchestraTestCase;
use Playground\Test\ServiceProvider;

/**
 * \Tests\Unit\Playground\Test\TestCase
 */
class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', 'Playground\\Test\\Models\\User');
        // $app['config']->set('playground-test.password', 'password');
    }
}
