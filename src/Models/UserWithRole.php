<?php

declare(strict_types=1);
/**
 * Playground
 */

namespace Playground\Test\Models;

use Database\Factories\Playground\Test\Models\UserWithRoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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
class UserWithRole extends AbstractUser
{
    /** @use HasFactory<UserWithRoleFactory> */
    use HasFactory;

    protected $attributes = [
        'name' => '',
        'email' => '',
        'role' => '',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'role',
    ];

    /**
     * Checks to see if the user has the role.
     *
     * @param  mixed  $roles  The role or roles to check.
     */
    public function hasRole(mixed $roles): bool
    {
        if (empty($roles)) {
            return false;
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if (! empty($role)
                    && $role === $this->getAttribute('role')
                ) {
                    return true;
                }
            }

            return false;
        }

        if (is_string($roles)
            && $roles === $this->getAttribute('role')
        ) {
            return true;
        }

        return false;
    }

    /**
     * Checks to see if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole([
            'wheel',
            'admin',
            'root',
        ]);
    }
}
