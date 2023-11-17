<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test;

use App\Models\User;

/**
 * \GammaMatrix\Playground\Test\AuthTrait
 *
 */
trait AuthTrait
{
    /**
     * @var array The available authentication roles.
     */
    protected $authRoles = [];

    /**
     * Initialize the authentication roles.
     *
     * @return void
     */
    public function initAuthRoles()
    {
        $config = config('playground-test');
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$config' => $config,
        // ]);

        if (empty($config['users']) || ! is_array($config['users'])) {
            error_log('No users defined in playground-test.');
            return;
        }

        foreach ($config['users'] as $type => $meta) {
            // Set the user email from the env var in phpunit.xml
            if (!empty($meta['env'])
                && !empty(env($meta['env']))
                && is_string(env($meta['env']))
            ) {
                $this->authRoles[$type]['email'] = env($meta['env']);
            }
            // Set the primary role.
            if (!empty($meta['role']) && is_string($meta['role'])) {
                $this->authRoles[$type]['role'] = $meta['role'];
            }
            // Set the additional roles on the user.
            if (!empty($meta['roles'])
                && is_array($meta['roles'])
            ) {
                $roles = [];
                foreach ($meta['roles'] as $role) {
                    if (!empty($role)
                        && is_string($role)
                        && !in_array($role, $roles)
                    ) {
                        $roles[] = $role;
                    }
                }
                $this->authRoles[$type]['roles'] = $roles;
            }
        }
    }

    /**
     * Act as a particular user type.
     *
     * NOTE This method will use a factory to create a user with a role if they do not exist.
     *
     * @param string $type user | admin | root | ...
     * @param boolean $create Create the user with the role if not found by email.
     */
    public function as($type, $create = true)
    {
        if (empty($this->authRoles[$type])) {
            throw new \Exception(sprintf(
                'No user type available for testing: {type: %s, types: %s}',
                $type,
                implode(', ', array_keys($this->authRoles))
            ));
        }

        $user = null;
        $role = empty($this->authRoles[$type]['role']) ? '' : $this->authRoles[$type]['role'];
        $email = empty($this->authRoles[$type]['email']) ? null : $this->authRoles[$type]['email'];
        $roles = empty($this->authRoles[$type]['roles']) ? [] : $this->authRoles[$type]['roles'];
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '__FILE__' => __FILE__,
        //     '__LINE__' => __LINE__,
        //     '$type' => $type,
        //     '$create' => $create,
        //     '$role' => $role,
        //     '$email' => $email,
        //     '$roles' => $roles,
        //     '$this->authRoles' => $this->authRoles,
        // ]);

        if (!empty($email)) {
            $user = User::where('email', 'LIKE', $email)->first();
        }

        if (empty($user) && $create) {
            $user = User::factory()->create([
                'role' => $role,
                'roles' => $roles,
            ]);
        }

        if (empty($user)) {
            throw new \Exception(sprintf(
                'No user available for testing: {type: %s, email: %s, role: %s}',
                $type,
                $email,
                $role
            ));
        }

        return $this->actingAs($user);
    }
}
