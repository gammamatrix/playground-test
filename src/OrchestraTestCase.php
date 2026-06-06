<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Test;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * \Playground\Test\OrchestraTestCase
 */
abstract class OrchestraTestCase extends Orchestra
{
    use Concerns\Migrations;
    use Concerns\Orchestrating;
    use WithFaker;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->orchestrateCarbon();
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $this->orchestrateUsers($app);
    }
}
