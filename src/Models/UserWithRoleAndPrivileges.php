<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

/**
 * \Playground\Test\Models\UserWithRoleAndPrivileges
 */
class UserWithRoleAndPrivileges extends User
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
