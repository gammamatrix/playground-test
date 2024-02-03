<?php
/**
 * Playground
 */
namespace Playground\Test\Models\Traits;

/**
 * \Playground\Test\Models\Traits\UserPrivileges
 *
 * @property string $password
 * @property string $role
 * @property array<int, string> $roles
 * @property array<int, string> $privileges
 */
trait UserPrivileges
{
    abstract public function getAttribute($key);

    /**
     * Checks to see if the user has the privilege.
     *
     * @param ?string $privilege The privilege to check.
     */
    public function hasPrivilege(mixed $privilege): bool
    {
        if (empty($privilege) || ! is_string($privilege)) {
            return false;
        }

        $privileges = $this->getAttribute('privileges');

        return is_array($privileges) && in_array($privilege, $privileges);
    }

    /**
     * Checks to see if the user has the role.
     *
     * @param ?string $role The role to check.
     */
    public function hasRole(mixed $role): bool
    {
        if (empty($role) || ! is_string($role)) {
            return false;
        }

        if ($role === $this->getAttribute('role')) {
            return true;
        }

        $roles = $this->getAttribute('roles');

        return is_array($roles) && in_array($role, $roles);
    }

    /**
     * Checks to see if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin')
            || $this->hasRole('wheel')
            || $this->getAttribute('role') === 'root';
    }
}
