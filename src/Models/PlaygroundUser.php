<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Models;

use Playground\Models\User as BaseUser;

/**
 * \Playground\Test\Models\PlaygroundUser
 *
 * NOTE: This model should be the same as a \App\Models\User extends \Playground\Models\User.
 */
class PlaygroundUser extends BaseUser
{
    protected $table = 'users';
}
