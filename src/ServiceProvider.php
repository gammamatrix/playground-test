<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test;

// use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * \GammaMatrix\Playground\Test\ServiceProvider
 *
 */
class ServiceProvider extends BaseServiceProvider
{
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

        if (!empty($config) && $this->app->runningInConsole()) {
            // Publish configuration
            $this->publishes([
                dirname(__DIR__).'/config/playground-test.php'
                    => config_path('playground-test.php')
            ], 'playground-config');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/playground-test.php',
            'playground-test'
        );
    }
}
