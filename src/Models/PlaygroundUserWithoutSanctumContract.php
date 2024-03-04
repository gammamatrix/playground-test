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
 * This model is for testing what happens when the Sanctum contract is not implemented.
 */
class PlaygroundUserWithoutSanctumContract extends BaseUser
{
    use Sanctum\HasApiTokens;

    protected $table = 'users';
}
