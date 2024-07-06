<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test;

use Illuminate\Support\Carbon;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * \Playground\Test\OrchestraTestCase
 */
abstract class OrchestraTestCase extends Orchestra
{
    use WithFaker;

    protected bool $setTestNow = true;

    protected bool $setUpUserCredentials = true;

    protected bool $setUpUserForAdmin = false;

    protected bool $setUpUserForLaravel = false;

    protected bool $setUpUserForLaravelSanctum = false;

    protected bool $setUpUserForPlayground = false;

    protected bool $setUpUserForPlaygroundSanctum = false;

    protected bool $setUpUserForPolicy = false;

    protected bool $setUpUserForPrivileges = false;

    protected bool $setUpUserForRoles = false;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->setTestNow) {
            Carbon::setTestNow(Carbon::now());
        }
    }

    /**
     * Define environment setup.
     *
     * Verification:
     * - admin: $user->isAdmin()
     * - policy: $user->can()
     * - privileges: $user->hasPrivilege()
     * - roles: $user->hasRole()
     * - sanctum: $user->currentAccessToken()->can()
     * - user: ! empty($user)
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $verify = '';
        $userModel = '';

        $sanctum = false;
        $hasPrivilege = false;
        $userPrivileges = false;
        $hasRole = false;
        $userRole = false;
        $userRoles = false;

        $password = env('AUTH_TESTING_PASSWORD', 'password');
        $hashed = boolval(env('AUTH_TESTING_HASHED', false));

        if ($this->setUpUserForPlayground) {

            $userModel = 'Playground\\Models\\User';

            $verify = 'roles';

            $hasPrivilege = true;
            $userPrivileges = true;
            $hasRole = true;
            $userRole = true;
            $userRoles = true;

        } elseif ($this->setUpUserForPlaygroundSanctum) {

            $userModel = 'Playground\\Test\\Models\\PlaygroundUserWithSanctum';

            $verify = 'sanctum';

            $sanctum = true;
            $hasPrivilege = true;
            $userPrivileges = true;
            $hasRole = true;
            $userRole = true;
            $userRoles = true;

        } elseif ($this->setUpUserForLaravel) {

            $userModel = 'Playground\\Test\\Models\\DefaultUser';
            $verify = 'user';

        } elseif ($this->setUpUserForAdmin) {

            $userModel = 'Playground\\Models\\User';
            $verify = 'admin';

        } elseif ($this->setUpUserForPolicy) {

            $userModel = 'Playground\\Models\\User';
            $verify = 'privileges';

        } elseif ($this->setUpUserForPrivileges) {

            $userModel = 'Playground\\Models\\User';
            $verify = 'policy';

        } elseif ($this->setUpUserForRoles) {

            $userModel = 'Playground\\Models\\User';
            $verify = 'roles';

        } elseif ($this->setUpUserForLaravelSanctum) {

            $userModel = 'Playground\\Test\\Models\\DefaultUser';

            $verify = 'sanctum';

            $sanctum = false;
        }

        if ($userModel) {
            $app['config']->set('auth.providers.users.model', $userModel);
        }

        if ($this->setUpUserCredentials) {
            $app['config']->set('auth.testing.password', $password);
            $app['config']->set('auth.testing.hashed', $hashed);
        }

        if ($verify) {
            $app['config']->set('playground-auth.verify', $verify);

            $app['config']->set('playground-auth.sanctum', $sanctum);
            $app['config']->set('playground-auth.hasPrivilege', $hasPrivilege);
            $app['config']->set('playground-auth.userPrivileges', $userPrivileges);
            $app['config']->set('playground-auth.hasRole', $hasRole);
            $app['config']->set('playground-auth.userRole', $userRole);
            $app['config']->set('playground-auth.userRoles', $userRoles);
        }
    }
}
