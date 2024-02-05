<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

/**
 * \Playground\Test\Models\UserWithRoleAndRolesAndPrivileges
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
        'privilieges' => [],
    ];
}
