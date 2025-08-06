<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Playground\Models\User;

/**
 * \CustomUsersTableSeeder
 *
 * Test users.
 */
class CustomUsersTableSeeder extends Seeder
{
    protected bool $withPrivileges = true;

    protected bool $withActive = true;

    protected bool $withDescription = true;

    protected bool $withRole = true;

    protected bool $withRoles = true;

    protected bool $withStatus = true;

    /**
     * @var class-string<User>
     */
    protected string $userClass = User::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = config('playground-test');

        if (! is_array($config) || empty($config['users']) || ! is_array($config['users'])) {
            error_log('No users defined in playground-test.');

            return;
        }

        /**
         * @var class-string<User> $userClass
         */
        $userClass = $this->userClass;

        if (! empty(config('auth.providers.users.model'))
            && is_string(config('auth.providers.users.model'))
            && class_exists(config('auth.providers.users.model'))
        ) {
            $userClass = config('auth.providers.users.model');
        }

        if (empty($config['with_active'])) {
            $this->withActive = false;
        }

        if (empty($config['with_description'])) {
            $this->withDescription = false;
        }

        if (empty($config['with_privileges'])) {
            $this->withPrivileges = false;
        }

        if (empty($config['with_role'])) {
            $this->withRole = false;
        }

        if (empty($config['with_roles'])) {
            $this->withRoles = false;
        }

        if (empty($config['with_status'])) {
            $this->withStatus = false;
        }

        $password = empty($config['password']) || ! is_string($config['password']) ? '' : $config['password'];
        // $password = 'testing';
        $password_encrypted = ! empty($config['password_encrypted']);

        if (empty($password)) {
            // Set a random password.
            $password = md5(date('c'));
            $password = Hash::make($password);
        } elseif (! $password_encrypted) {
            $password = Hash::make($password);
        }

        foreach ($config['users'] as $slug => $meta) {

            if (! is_string($slug) || ! is_array($meta)) {
                \Log::warning(sprintf('Invalid test user[%s]', $slug));

                continue;
            }
            if (! empty($meta['email']) && is_string($meta['email'])) {
                $email = $meta['email'];
            } else {
                $email = sprintf('%1$s@example.com', Str::slug($slug));
            }

            /**
             * @var Builder<User> $query
             */
            $query = $userClass::where('email', $email);

            /**
             * @var User $model|null
             */
            $model = $query->first();

            $data = [
                'name' => empty($meta['name']) || ! is_string($meta['name']) ? 'Some Name' : $meta['name'],
            ];

            if ($this->withActive) {
                $data['active'] = true;
            }

            if ($this->withRole) {
                $data['description'] = empty($meta['description']) || ! is_string($meta['description']) ? '' : $meta['description'];
            }

            if ($this->withRole) {
                $data['role'] = empty($meta['role']) || ! is_string($meta['role']) ? '' : $meta['role'];
            }

            if ($this->withRole) {
                $data['status'] = empty($meta['status']) || ! is_numeric($meta['status']) ? 0 : $meta['status'];
            }

            if (empty($model)) {
                $data['email'] = $email;
                /**
                 * @var User $model
                 */
                $model = $userClass::create($data);
            } else {
                $model->update($data);
            }

            if ($this->withRoles && is_array($meta['roles'])) {
                $roles = [];
                foreach ($meta['roles'] as $role) {
                    if (! empty($role)
                        && is_string($role)
                        && ! in_array($role, $roles)
                        && $role !== $model->role
                    ) {
                        $roles[] = $role;
                    }
                }
                $model->roles = $roles;
            }

            if ($this->withPrivileges && is_array($meta['privileges'])) {
                $privileges = [];
                foreach ($meta['privileges'] as $privilege) {
                    if (! empty($privilege)
                        && is_string($privilege)
                        && ! in_array($privilege, $privileges)
                    ) {
                        $privileges[] = $privilege;
                    }
                }
                $model->privileges = $privileges;
            }

            // dd([
            //     '__METHOD__' => __METHOD__,
            //     '$password' => $password,
            //     '$password_encrypted' => $password_encrypted,
            //     '$email' => $email,
            //     '$slug' => $slug,
            //     '$meta' => $meta,
            //     '$roles' => $roles,
            //     '$privileges' => $privileges,
            //     // '$config' => $config,
            //     // '$model' => $model->toArray(),
            //     '$model' => $model,
            // ]);

            // Reset the password
            $model->setAttribute('password', $password);
            $model->save();
        }
    }
}
