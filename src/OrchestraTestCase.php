<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test;

use Orchestra\Testbench\TestCase as Orchestra;

/**
 * \GammaMatrix\Playground\Test\OrchestraTestCase
 *
 */
abstract class OrchestraTestCase extends Orchestra
{
    use WithFaker;
}
