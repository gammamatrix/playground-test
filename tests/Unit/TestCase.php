<?php

declare(strict_types=1);
/**
 * Playground
 */

namespace Tests\Unit\Playground\Test;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Playground\Test\OrchestraTestCase;

/**
 * \Tests\Unit\Playground\Test\TestCase
 */
class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions;
    use PackageProviders;
}
