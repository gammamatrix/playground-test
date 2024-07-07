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
 */
trait Migrations
{
    protected bool $load_migrations_laravel = false;

    protected string $load_migrations_playground_test;

    protected string $load_migrations_package = '';

    protected string $load_migrations_package_migration = '';

    protected bool $load_migrations_playground = false;

    /**
     * Define database migrations.
     *
     * @api
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        if (! empty(env('TEST_DB_MIGRATIONS'))) {

            $folderForVendor = $this->verifyPlaygroundTestExists();

            if ($this->load_migrations_laravel) {
                $this->loadMigrationsFrom($folderForVendor.'/gammamatrix/playground-test/database/migrations-laravel');
            }
            if ($this->load_migrations_playground) {
                $this->loadMigrationsFrom($folderForVendor.'/gammamatrix/playground-test/database/migrations-playground');
            }
            if ($this->load_migrations_package) {
                $this->loadPackageMigration($folderForVendor, $this->load_migrations_package);
            }
        }
    }

    protected function loadPackageMigration(
        string $folderForVendor,
        string $package,
        string $migrations = null
    ): void {

        $organization = Str::of($package)->before('/')->toString();
        $organization_package = Str::of($package)->after('/')->toString();

        if (empty($organization)) {
            throw new ValueError('Expecting the Packagist Organization to be provided.');
        }

        if (empty($organization_package)) {
            throw new ValueError(sprintf(
                'Expecting the Packagist Organization [%1$s] Package to be provided in the $package [%2$s]',
                $organization,
                $package
            ));
        }

        $folderForOrganization = sprintf('%1$s/%2$s', $folderForVendor, $organization);

        if (! is_dir($folderForOrganization)) {
            throw new ValueError(sprintf(
                'Expecting the Composer vendor folder for [%1$s] to exist: [%2$s]',
                $organization,
                $folderForOrganization
            ));
        }

        $folderForOrganizationPackage = sprintf('%1$s/%2$s', $folderForOrganization, $organization_package);

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

        if (is_null($migrations)) {
            if ($this->load_migrations_package_migration) {
                $migrations = trim($this->load_migrations_package_migration, '/\\');
            } else {
                $migrations = 'migrations';
            }
        }

        if (empty($migrations)) {
            throw new ValueError(sprintf(
                'Expecting a folder, for the set of migrations, to be provided: [%1$s]',
                $folderForOrganizationPackageDatabase
            ));
        }

        $folderForMigrations = $folderForOrganizationPackageDatabase.'/'.$migrations;

        if (! is_dir($folderForMigrations)) {
            throw new ValueError(sprintf(
                'Expecting the organization [%1$s] to have a set of database migrations: [%2$s]',
                $organization,
                $folderForMigrations
            ));
        }

        $this->loadMigrationsFrom($folderForMigrations);
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

    protected function loadPlaygroundMigration(string $folder): void
    {
        $playground_database = sprintf('%1$s/playground/database', dirname(dirname(dirname(__DIR__))));
        $migrations = sprintf('%1$s/%2$s', $playground_database, $folder);

        if ($folder && is_dir($playground_database) && is_dir($migrations)) {
            $this->loadMigrationsFrom($playground_database.'/'.$folder);
        }
    }
}
