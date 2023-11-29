<?php
/**
 * GammaMatrix
 */

namespace GammaMatrix\Playground\Test\Models;

use Laravel\Sanctum\HasApiTokens;

/**
 * \GammaMatrix\Playground\Test\Models\UserWithSanctum
 *
 */
class UserWithSanctum extends AbstractUser
{
    use HasApiTokens;
}
