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
 * \Playground\Test\Models\UserWithRoleAndRoles
 *
 * @property int $id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $email_verified_at
 * @property string $name
 * @property string $email
 * @property string $role
 * @property array<int, string> $roles
 */
class UserWithRoleAndRoles extends AbstractUser
{
    use Concerns\UserPrivileges;

    /** @use HasFactory<UserWithRoleFactory> */
    use HasFactory;

    protected $attributes = [
        'name' => '',
        'email' => '',
        'role' => '',
        'roles' => [],
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'role',
        'roles',
    ];
}
