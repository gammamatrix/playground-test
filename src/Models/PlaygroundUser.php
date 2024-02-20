<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Playground\Models\Interfaces\WithCreatorInterface;
use Playground\Models\Interfaces\WithModifierInterface;
use Playground\Models\Traits\WithCreator;
use Playground\Models\Traits\WithModifier;
use Playground\Models\User as BaseUser;

/**
 * \Playground\Test\Models\PlaygroundUser
 *
 * This model includes RBAC and DOES NOT support Sanctum.
 */
class PlaygroundUser extends BaseUser implements WithCreatorInterface, WithModifierInterface
{
    use WithCreator;
    use WithModifier;

    protected $table = 'users';
}
