<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Models;

use GammaMatrix\Playground\Models\Traits\UserPrivileges;

/**
 * \GammaMatrix\Playground\Test\Models\UserWithRoleAndRoles
 *
 */
class UserWithRoleAndRoles extends AbstractUser
{
    use UserPrivileges;

    protected $attributes = [
        'role' => '',
        'roles' => [],
    ];
}