<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

/**
 * \Playground\Test\Models\UserWithRoleAndRoles
 */
class UserWithRoleAndRoles extends AbstractUser
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
    ];
}
