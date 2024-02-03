<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

/**
 * \Playground\Test\Models\UserWithRoleAndRolesAndPrivileges
 */
class UserWithRoleAndRolesAndPrivileges extends AbstractUser
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
        'privilieges' => [],
    ];
}
