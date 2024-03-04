<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

/**
 * \Playground\Test\Models\UserWithRoleAndRolesAndPrivileges
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
class UserWithRoleAndRolesAndPrivileges extends User
{
    use Traits\UserPrivileges;

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
