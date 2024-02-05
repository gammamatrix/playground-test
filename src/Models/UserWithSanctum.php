<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Laravel\Sanctum\HasApiTokens;

/**
 * \Playground\Test\Models\UserWithSanctum
 */
class UserWithSanctum extends User
{
    use HasApiTokens;
}
