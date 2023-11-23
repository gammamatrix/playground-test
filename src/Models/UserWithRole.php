<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Models;

/**
 * \GammaMatrix\Playground\Test\Models\UserWithRoleAndRoles
 *
 */
class UserWithRole extends AbstractUser
{
    protected $attributes = [
        'name' => '',
        'email' => '',
        'role' => '',
    ];

    /**
     * Checks to see if the user has the role.
     *
     * @param string $role The role to check.
     */
    public function hasRole($role): bool
    {
        if (empty($role) || !is_string($role)) {
            return false;
        }

        if ($role === $this->getAttribute('role')) {
            return true;
        }

        return false;
    }

    /**
     * Checks to see if the user is an admin.
     *
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin')
            || $this->hasRole('wheel')
            || $this->hasRole('root')
        ;
    }
}
