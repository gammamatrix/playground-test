<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Models;

/**
 * \GammaMatrix\Playground\Test\Models\UserWithRoleAndRoles
 *
 */
class UserWithRoleAndRoles extends AbstractUser
{
    use Traits\UserPrivileges;

    protected $attributes = [
        'name' => '',
        'email' => '',
        'role' => '',
        'roles' => [],
    ];
}
