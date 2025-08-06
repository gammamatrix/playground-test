<?php

declare(strict_types=1);
/**
 * Playground
 */

namespace Tests\Feature\Playground\Test;

/**
 * \Tests\Feature\Playground\Test\TestCase
 */
class TestCase extends \Tests\Unit\Playground\Test\TestCase
{
    protected bool $load_migrations_laravel = false;

    protected bool $load_migrations_testing = false;

    /**
     * Define database migrations.
     *
     * @api
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        if (! empty(config('playground-test.db.migrations'))) {
        }
    }
}
