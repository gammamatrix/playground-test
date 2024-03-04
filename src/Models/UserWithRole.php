<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

/**
 * \Playground\Test\Models\UserWithRole
 *
 * @property int $id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $email_verified_at
 * @property string $name
 * @property string $email
 * @property string $role
 */
class UserWithRole extends User
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
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'role',
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
