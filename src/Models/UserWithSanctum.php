<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
use Laravel\Sanctum\HasApiTokens;

/**
 * \Playground\Test\Models\UserWithSanctum
 */
class UserWithSanctum extends User implements HasApiTokensContract
{
    use HasApiTokens;
}
