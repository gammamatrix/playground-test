<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Models\Concerns;

/**
 * \Playground\Test\Models\Concerns\UserPrivileges
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
    public function hasPrivilege(mixed $privilege, bool $inclusive = false): bool
    {
        if (empty($privilege) || ! is_string($privilege)) {
            return false;
        }

        // TODO add in inclusive handling
        $privileges = $this->getAttribute('privileges');

        return is_array($privileges) && in_array($privilege, $privileges);
    }

    /**
     * Checks to see if the user has the role.
     *
     * @param mixed $roles The role to check.
     */
    public function hasRole(mixed $roles, bool $inclusive = false): bool
    {
        if (empty($roles)) {
            return false;
        }

        $user_roles = $this->getAttribute('roles');
        $user_roles = empty($user_roles) || ! is_array($user_roles) ? [] : $user_roles;

        if (is_array($roles)) {
            $required_roles = $inclusive ? count($roles) : 1;
            $contains = [];
            foreach ($roles as $role) {
                if (! empty($role) && (
                    $role === $this->getAttribute('role')
                    || in_array($role, $user_roles)
                )) {
                    $contains[] = $role;
                }
            }

            return ! empty($contains) && count($contains) === $required_roles;
        }

        if (! is_string($roles)) {
            return false;
        }

        if ($roles === $this->getAttribute('role')) {
            return true;
        }

        return in_array($roles, $user_roles);
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
