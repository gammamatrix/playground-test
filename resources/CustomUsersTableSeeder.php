<?php
/**
 * GammaMatrix
 *
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Str;

/**
 * \CustomUsersTableSeeder
 *
 * Test users.
 */
class CustomUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = config('playground-test');
        $password = empty($config['password']) ? '' : $config['password'];
        // $password = 'testing';
        $password_encrypted = ! empty($config['password_encrypted']);

        if (empty($config['users']) || ! is_array($config['users'])) {
            error_log('No users defined in playground-test.');
            return;
        }

        if (empty($password) || !is_string($password)) {
            // Set a random password.
            $password = md5(time());
            $password = Hash::make($password);
        } elseif (!$password_encrypted) {
            $password = Hash::make($password);
        }

        foreach ($config['users'] as $slug => $meta) {

            $email = sprintf('%1$s@example.com', Str::slug($slug));

            $model = User::where('email', $email)->first();

            if (empty($model)) {
                $model = new User([
                    'name' => empty($meta['name']) || !is_string($meta['name']) ? 'Some Name' : $meta['name'],
                    'description' => empty($meta['description']) || !is_string($meta['description']) ? '' : $meta['description'],
                    'active' => true,
                    'email' => $email,
                    'role' => empty($meta['role']) || !is_string($meta['role']) ? '' : $meta['role'],
                    'status' => empty($meta['status']) || !is_numeric($meta['status']) ? 0 : $meta['status'],
                ]);
            } else {
                $model->update([
                    'name' => empty($meta['name']) || !is_string($meta['name']) ? 'Some Name' : $meta['name'],
                    'description' => empty($meta['description']) || !is_string($meta['description']) ? '' : $meta['description'],
                    'active' => true,
                    'role' => empty($meta['role']) || !is_string($meta['role']) ? '' : $meta['role'],
                    'status' => empty($meta['status']) || !is_numeric($meta['status']) ? 0 : $meta['status'],
                ]);
            }

            $roles = [];

            if (is_array($meta['roles'])) {
                foreach ($meta['roles'] as $role) {
                    if (!empty($role)
                        && is_string($role)
                        && !in_array($role, $roles)
                        && $role !== $model->role
                    ) {
                        $roles[] = $role;
                    }
                }
            }

            $model->roles = $roles;

            // Reset the password
            $model->password = $password;
            $model->save();
        }

        return $this;
    }
}
