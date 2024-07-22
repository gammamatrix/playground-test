<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Models;

use Database\Factories\Playground\Test\Models\UserWithRoleAndRolesAndPrivilegesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * \Playground\Test\Models\UserWithRoleAndPrivileges
 *
 * @property int $id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $email_verified_at
 * @property string $name
 * @property string $email
 * @property string $role
 * @property array $roles
 * @property array $privileges
 */
class UserWithRoleAndPrivileges extends AbstractUser
{
    use Concerns\UserPrivileges;

    /** @use HasFactory<UserWithRoleAndRolesAndPrivilegesFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'name' => '',
        'email' => '',
        'role' => '',
        'roles' => [],
        'privileges' => [],
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
        'roles',
        'privileges',
    ];
}
