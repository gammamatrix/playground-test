<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Playground\Models\User as BaseUser;

/**
 * \Playground\Test\Models\PlaygroundUser
 *
 * This model includes RBAC and DOES NOT support Sanctum.
 */
class PlaygroundUser extends BaseUser
{
    protected $table = 'users';
}
