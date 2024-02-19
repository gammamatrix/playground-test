<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Test\Console\Commands\About;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\ServiceProvider;
use Tests\Feature\Playground\Test\TestCase;

/**
 * \Tests\Feature\Playground\Test\Console\Commands\About
 */
#[CoversClass(ServiceProvider::class)]
class CommandTest extends TestCase
{
    public function test_command_about_displays_package_information_and_succeed_with_code_0(): void
    {
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('about');
        $result->assertExitCode(0);
        $result->expectsOutputToContain('Playground: Test');
    }
}
