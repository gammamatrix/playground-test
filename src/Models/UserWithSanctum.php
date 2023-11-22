<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Models;

use Laravel\Sanctum\HasApiTokens;

/**
 * \GammaMatrix\Playground\Test\Models\User
 *
 */
class UserWithSanctum extends AbstractUser
{
    use HasApiTokens;
}
