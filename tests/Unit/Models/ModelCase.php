<?php

/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Test\Models;

use Playground\ServiceProvider as PlaygroundServiceProvider;
use Playground\Test\ServiceProvider;
use Playground\Test\Unit\Models\ModelCase as BaseModelCase;

/**
 * \Tests\Unit\Playground\Test\Models\ModelCase
 */
class ModelCase extends BaseModelCase
{
    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        // $this->loadMigrationsFrom(workbench_path('database/migrations'));
        $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migration-testing');
    }

    protected function getPackageProviders($app)
    {
        return [
            PlaygroundServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    // /**
    //  * Set up the environment.
    //  *
    //  * @param  \Illuminate\Foundation\Application  $app
    //  */
    // protected function getEnvironmentSetUp($app)
    // {
    //     $app['config']->set('auth.providers.users.model', 'Playground\\Models\\User');
    //     $app['config']->set('playground-auth.verify', 'user');
    //     $app['config']->set('auth.testing.password', 'password');
    //     $app['config']->set('auth.testing.hashed', false);

    //     // $app['config']->set('playground-test.load.migrations', true);
    // }
}
