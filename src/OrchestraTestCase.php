<?php
/**
 * Playground
 */
namespace Playground\Test;

use Orchestra\Testbench\TestCase as Orchestra;

/**
 * \Playground\Test\OrchestraTestCase
 */
abstract class OrchestraTestCase extends Orchestra
{
    use WithFaker;
}
