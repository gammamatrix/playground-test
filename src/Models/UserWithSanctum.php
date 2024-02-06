<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;

/**
 * \Playground\Test\Models\UserWithSanctum
 */
class UserWithSanctum extends User implements HasApiTokensContract
{
    use HasApiTokens;
}
