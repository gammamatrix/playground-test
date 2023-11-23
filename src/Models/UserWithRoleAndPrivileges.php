<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Models;

/**
 * \GammaMatrix\Playground\Test\Models\UserWithRoleAndPrivileges
 *
 */
class UserWithRoleAndPrivileges extends AbstractUser
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
