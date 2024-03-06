<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * \Playground\Test\ServiceProvider
 */
class ServiceProvider extends BaseServiceProvider
{
    public const VERSION = '73.0.0';

    public string $package = 'playground-test';

    /**
     * Bootstrap any package services.
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $config = config('playground-test');

        if (! empty($config) && $this->app->runningInConsole()) {
            // Publish configuration
            $this->publishes([
                dirname(__DIR__).'/config/playground-test.php' => config_path('playground-test.php'),
            ], 'playground-config');
        }

        $this->about();
    }

    public function about(): void
    {
        $config = config($this->package);
        $config = is_array($config) ? $config : [];

        $users = ! empty($config['users']) && is_array($config['users']) ? $config['users'] : [];
        $testPassword = ! empty($config['password']) && is_string($config['password']) ? str_repeat('*', strlen($config['password'])) : '';
        $testPasswordEncrypted = ! empty($config['password_encrypted']);

        $version = $this->version();

        AboutCommand::add('Playground: Test', fn () => [
            '<fg=yellow;options=bold>PLAYGROUND_TEST_PASSWORD_ENCRYPTED</>' => $testPasswordEncrypted ? '<fg=green;options=bold>ENCRYPTED</>' : '<fg=yellow;options=bold>PLAIN TEXT</>',
            '<fg=green;options=bold>User</> Lists' => sprintf('[%s]', implode(', ', array_keys($users))),
            '<fg=green;options=bold>PLAYGROUND_TEST_PASSWORD</> Save' => sprintf('[%s]', $testPassword),
            'Package' => $this->package,
            'Version' => $version,
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            sprintf('%1$s/config/%2$s.php', dirname(__DIR__), $this->package),
            $this->package
        );
    }

    public function version(): string
    {
        return static::VERSION;
    }
}
