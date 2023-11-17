<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * \GammaMatrix\Playground\Test\TestCase
 *
 */
abstract class TestCase extends \Tests\TestCase
{
    use DatabaseTransactions;
    use WithFaker;
}
