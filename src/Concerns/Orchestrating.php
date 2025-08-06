<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Test\Concerns;

use Illuminate\Support\Carbon;
use Playground\Models\User;
use Playground\Test\Models\DefaultUser;
use Playground\Test\Models\UserWithSanctum;

/**
 * \Playground\Test\Concerns\Orchestrating
 */
trait Orchestrating
{
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

    protected function orchestrateCarbon(): void
    {
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
     */
    protected function orchestrateUsers($app): void
    {
        $verify = '';
        $userModel = '';

        $sanctum = false;
        $hasPrivilege = false;
        $userPrivileges = false;
        $hasRole = false;
        $userRole = false;
        $userRoles = false;

        $password = $app['config']->get('playground-test.password');
        // $password = env('AUTH_TESTING_PASSWORD', 'password');
        $hashed = $app['config']->get('playground-test.password_encrypted');
        // $hashed = boolval(env('AUTH_TESTING_HASHED', false));

        if ($this->setUpUserForPlayground) {

            $userModel = User::class;

            $verify = 'roles';

            $hasPrivilege = true;
            $userPrivileges = true;
            $hasRole = true;
            $userRole = true;
            $userRoles = true;

        } elseif ($this->setUpUserForPlaygroundSanctum) {

            $userModel = User::class;

            $verify = 'sanctum';

            $sanctum = true;
            $hasPrivilege = true;
            $userPrivileges = true;
            $hasRole = true;
            $userRole = true;
            $userRoles = true;

        } elseif ($this->setUpUserForLaravel) {

            $userModel = DefaultUser::class;
            $verify = 'user';

        } elseif ($this->setUpUserForAdmin) {

            $userModel = User::class;
            $verify = 'admin';

        } elseif ($this->setUpUserForPolicy) {

            $userModel = User::class;
            $verify = 'privileges';

        } elseif ($this->setUpUserForPrivileges) {

            $userModel = User::class;
            $verify = 'policy';

        } elseif ($this->setUpUserForRoles) {

            $userModel = User::class;
            $verify = 'roles';

        } elseif ($this->setUpUserForLaravelSanctum) {

            $userModel = UserWithSanctum::class;

            $verify = 'sanctum';

            $sanctum = false;
        }

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$verify' => $verify,
        //     '$userModel' => $userModel,
        //     '$sanctum' => $sanctum,
        //     '$hasPrivilege' => $hasPrivilege,
        //     '$userPrivileges' => $userPrivileges,
        //     '$hasRole' => $hasRole,
        //     '$userRole' => $userRole,
        //     '$userRoles' => $userRoles,
        //     '$password' => $password,
        //     '$hashed' => $hashed,
        //     '$this->setUpUserForPlayground' => $this->setUpUserForPlayground,
        // ]);

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
