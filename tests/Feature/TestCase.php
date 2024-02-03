<?php
/**
 * Playground
 */
namespace Tests\Feature\Playground\Test;

/**
 * \Tests\Feature\Playground\Test\TestCase
 */
class TestCase extends \Tests\Unit\Playground\Test\TestCase
{
    protected bool $load_migrations_laravel = false;

    protected bool $load_migrations_testing = false;

    protected bool $load_User = false;
    protected bool $load_UserWithChildren = false;
    protected bool $load_UserWithRole = false;
    protected bool $load_UserWithRoleAndPrivileges = false;
    protected bool $load_UserWithRoleAndRoles = false;
    protected bool $load_UserWithRoleAndRolesAndPrivileges = false;
    protected bool $load_UserWithSanctum = false;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     'TEST_DB_MIGRATIONS' => ! empty(env('TEST_DB_MIGRATIONS')),
        //     'database' => dirname(dirname(__DIR__)).'/database',
        // ]);
        if (! empty(env('TEST_DB_MIGRATIONS'))) {
            // $this->loadLaravelMigrations();
            if ($this->load_migrations_laravel) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-laravel');
            }
            if ($this->load_migrations_testing) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-testing');
            }
            if ($this->load_User) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-load_User');
            }
            if ($this->load_UserWithChildren) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-load_UserWithChildren');
            }
            if ($this->load_UserWithRole) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-load_UserWithRole');
            }
            if ($this->load_UserWithRoleAndPrivileges) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-load_UserWithRoleAndPrivileges');
            }
            if ($this->load_UserWithRoleAndRoles) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-load_UserWithRoleAndRoles');
            }
            if ($this->load_UserWithRoleAndRolesAndPrivileges) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-load_UserWithRoleAndRolesAndPrivileges');
            }
            if ($this->load_UserWithSanctum) {
                $this->loadMigrationsFrom(dirname(dirname(__DIR__)).'/database/migrations-load_UserWithSanctum');
            }
        }
    }
}
