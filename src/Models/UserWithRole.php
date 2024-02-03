<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

/**
 * \Playground\Test\Models\UserWithRole
 */
class UserWithRole extends AbstractUser
{
    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'name' => '',
        'email' => '',
        'role' => '',
    ];

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

        return false;
    }

    /**
     * Checks to see if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin')
            || $this->hasRole('wheel')
            || $this->hasRole('root');
    }
}
