<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Laravel\Sanctum;
use Playground\Models\User as BaseUser;

/**
 * \Playground\Test\Models\PlaygroundUserWithSanctum
 *
 * This model includes RBAC and supports Sanctum.
 */
class PlaygroundUserWithSanctum extends BaseUser implements Sanctum\Contracts\HasApiTokens
{
    use Sanctum\HasApiTokens;

    protected $table = 'users';
}
