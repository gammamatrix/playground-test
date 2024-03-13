<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Database\Factories\Playground\Test\Models;

use Playground\Test\Models\User;

/**
 * \Database\Factories\Playground\Test\Models\UserFactory
 */
class UserFactory extends DefaultUserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<User>
     */
    protected $model = User::class;
}
