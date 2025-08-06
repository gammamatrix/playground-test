<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Test\Concerns;

use Illuminate\Support\Str;
use ValueError;

/**
 * \Playground\Test\Concerns\Migrations
 *
 * @property string $package_providers_dir
 */
trait Migrations
{
    protected bool $hasMigrations = false;

    /**
     * @var array<string, array<string, array<int, string>>>
     */
    protected array $load_migrations = [
        // Grouped by organizations and keyed by packages.
        // 'gammamatrix' => [
        //     'playground-cms' => [],
        //     'playground-leads' => [],
        //     'playground-matrix' => [],
        //     'playground-test' => [
        //         'migrations-testing',
        //     ],
        // ],
    ];

    protected bool $load_migrations_laravel = false;

    protected bool $load_migrations_package = false;

    protected bool $load_migrations_playground = false;

    protected string $load_migrations_playground_test;

    /**
     * Define database migrations.
     *
     * @api
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        if (! empty($this->hasMigrations) && ! empty(config('playground-test.db.migrations'))) {

            $folderForVendor = $this->verifyPlaygroundTestExists();

            if ($this->load_migrations_laravel) {
                $this->loadMigrationsFrom($folderForVendor.'/gammamatrix/playground-test/database/migrations-laravel');
            }
            if ($this->load_migrations_playground) {
                $this->loadMigrationsFrom($folderForVendor.'/gammamatrix/playground-test/database/migrations-playground');
            }
            if ($this->load_migrations_package) {
                $this->loadPackageMigrations();
            }
            if ($this->load_migrations) {
                $this->loadMigrationsFromPackages($folderForVendor);
            }
        }
    }

    protected function loadPackageMigrations(): void
    {
        if (empty($this->package_providers_dir)
            || ! is_string($this->package_providers_dir)
            || ! Str::endsWith($this->package_providers_dir, '/tests/Unit')
        ) {
            throw new ValueError(
                'Expecting package_providers_dir to be set in PackageProviders'
            );
        }

        $folderForPackage = Str::of($this->package_providers_dir)->before('/tests/Unit')->toString();

        $folderForPackageMigrations = sprintf('%1$s/database/migrations', $folderForPackage);

        if (! is_dir($folderForPackageMigrations)) {
            throw new ValueError(sprintf(
                'Expecting the package to have database migrations: [%1$s]',
                $folderForPackageMigrations
            ));
        }

        $this->loadMigrationsFrom($folderForPackageMigrations);
    }

    /**
     * @param  array<string, array<int, string>>  $packages
     */
    private function loadMigrationsFromPackages_org(
        string $folderForOrganization,
        string $organization,
        array $packages
    ): void {
        foreach ($packages as $package => $migrations) {

            if (empty($package) || ! is_string($package)) {
                throw new ValueError(sprintf(
                    'Expecting the package to be provided from the Packagist Organization [%1$s]: [%2$s] ',
                    $organization,
                    $folderForOrganization
                ));
            }

            if (empty($migrations) || ! is_array($migrations)) {
                $migrations = [
                    'migrations',
                ];
            }

            $folderForOrganizationPackage = sprintf('%1$s/%2$s', $folderForOrganization, $package);
            // dump([
            //     '__METHOD__' => __METHOD__,
            //     '$package' => $package,
            //     '$migrations' => $migrations,
            //     '$organization' => $organization,
            //     '$folderForOrganization' => $folderForOrganization,
            //     '$folderForOrganizationPackage' => $folderForOrganizationPackage,
            // ]);
            if (! is_dir($folderForOrganizationPackage)) {
                throw new ValueError(sprintf(
                    'Expecting the package to be found under Composer vendor folder for [%1$s]: [%2$s]',
                    $package,
                    $folderForOrganizationPackage
                ));
            }

            $folderForOrganizationPackageDatabase = $folderForOrganizationPackage.'/database';

            if (! is_dir($folderForOrganizationPackageDatabase)) {
                throw new ValueError(sprintf(
                    'Expecting the organization [%1$s] to have a database folder under: [%2$s]',
                    $organization,
                    $folderForOrganizationPackageDatabase
                ));
            }

            // if (empty($migrations)) {
            //     throw new ValueError(sprintf(
            //         'Expecting a folder, for the set of migrations, to be provided: [%1$s]',
            //         $folderForOrganizationPackageDatabase
            //     ));
            // }

            $this->loadMigrationsFromPackages_migrations(
                $folderForOrganizationPackageDatabase,
                $organization,
                $package,
                $migrations,
            );
        }
    }

    /**
     * @param  array<int, string>  $migrations
     */
    private function loadMigrationsFromPackages_migrations(
        string $folderForOrganizationPackageDatabase,
        string $organization,
        string $package,
        array $migrations
    ): void {

        foreach ($migrations as $migration) {
            $folderForMigrations = $folderForOrganizationPackageDatabase.'/'.$migration;

            if (! is_dir($folderForMigrations)) {
                throw new ValueError(sprintf(
                    'Expecting the organization [%1$s] to have a set of database migrations: [%2$s]',
                    $organization,
                    $folderForMigrations
                ));
            }

            if (! is_dir($folderForOrganizationPackageDatabase)) {
                throw new ValueError(sprintf(
                    'Expecting the organization [%1$s] to have a database folder under: [%2$s]',
                    $organization,
                    $folderForOrganizationPackageDatabase
                ));
            }

            // dd([
            //     '__METHOD__' => __METHOD__,
            //     '$folderForOrganizationPackageDatabase' => $folderForOrganizationPackageDatabase,
            //     '$folderForMigrations' => $folderForMigrations,
            //     '$migration' => $migration,
            //     // '$migrations' => $migrations,
            //     '$organization' => $organization,
            //     '$package' => $package,
            //     // '$packages' => $packages,
            //     // '$this->load_migrations' => $this->load_migrations,
            // ]);

            $this->loadMigrationsFrom($folderForMigrations);
        }
    }

    protected function loadMigrationsFromPackages(string $folderForVendor): void
    {
        foreach ($this->load_migrations as $organization => $packages) {

            if (empty($organization)) {
                throw new ValueError('Expecting the Packagist Organization to be provided.');
            }

            $folderForOrganization = sprintf('%1$s/%2$s', $folderForVendor, $organization);

            if (! is_dir($folderForOrganization)) {
                throw new ValueError(sprintf(
                    'Expecting the Composer vendor folder for [%1$s] to exist: [%2$s]',
                    $organization,
                    $folderForOrganization
                ));
            }

            $this->loadMigrationsFromPackages_org(
                $folderForOrganization,
                $organization,
                $packages,
            );
        }
    }

    /**
     * @return string Returns the path to the composer vendor folder.
     */
    private function verifyPlaygroundTestExists(): string
    {
        $folderForVendor = dirname(dirname(dirname(dirname(__DIR__))));

        if (! is_dir($folderForVendor)) {
            throw new ValueError(sprintf(
                'Expecting the Composer vendor folder to exist: [%1$s]',
                $folderForVendor
            ));
        }

        $folderForGammamatrix = sprintf('%1$s/%2$s', $folderForVendor, 'gammamatrix');

        if (! is_dir($folderForGammamatrix)) {
            throw new ValueError(sprintf(
                'Expecting the Composer vendor folder for gammamatrix to exist: [%1$s]',
                $folderForGammamatrix
            ));
        }

        $folderForPlaygroundTest = sprintf('%1$s/%2$s', $folderForGammamatrix, 'playground-test');

        if (! is_dir($folderForPlaygroundTest)) {
            throw new ValueError(sprintf(
                'Expecting the Composer vendor folder for gammamatrix/playground-test to exist: [%1$s]',
                $folderForPlaygroundTest
            ));
        }

        $folderForPlaygroundTest = sprintf('%1$s/%2$s', $folderForGammamatrix, 'playground-test');

        if (! is_dir($folderForPlaygroundTest)) {
            throw new ValueError(sprintf(
                'Expecting the Composer vendor folder for gammamatrix/playground-test to exist: [%1$s]',
                $folderForPlaygroundTest
            ));
        }

        $this->load_migrations_playground_test = sprintf('%1$s/database', $folderForPlaygroundTest);

        if (! is_dir($this->load_migrations_playground_test)) {
            throw new ValueError(sprintf(
                'Expecting the Composer vendor folder for gammamatrix/playground-test/database to exist: [%1$s]',
                $this->load_migrations_playground_test
            ));
        }

        $folderForPlaygroundTestLaravelMigrations = sprintf('%1$s/%2$s', $this->load_migrations_playground_test, 'migrations-laravel');

        if (! is_dir($folderForPlaygroundTestLaravelMigrations)) {
            throw new ValueError(sprintf(
                'Expecting the Composer vendor folder for gammamatrix/playground-test/database/migrations-laravel to exist: [%1$s]',
                $folderForPlaygroundTestLaravelMigrations
            ));
        }

        $folderForPlaygroundTestPlaygroundMigrations = sprintf('%1$s/%2$s', $this->load_migrations_playground_test, 'migrations-playground');

        if (! is_dir($folderForPlaygroundTestPlaygroundMigrations)) {
            throw new ValueError(sprintf(
                'Expecting the Composer vendor folder for gammamatrix/playground-test/database/migrations-playground to exist: [%1$s]',
                $folderForPlaygroundTestPlaygroundMigrations
            ));
        }

        return $folderForVendor;
    }
}
