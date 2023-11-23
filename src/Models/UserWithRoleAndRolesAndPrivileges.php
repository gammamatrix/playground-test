<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Models;

/**
 * \GammaMatrix\Playground\Test\Models\UserWithRoleAndRolesAndPrivileges
 *
 */
class UserWithRoleAndRolesAndPrivileges extends AbstractUser
{
    use Traits\UserPrivileges;

    protected $attributes = [
        'name' => '',
        'email' => '',
        'role' => '',
        'roles' => [],
        'privilieges' => [],
    ];
}
