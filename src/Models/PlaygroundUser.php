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
 * This model has the minimum Playground features.
 */
class PlaygroundUser extends BaseUser
{
    protected $table = 'users';
}
