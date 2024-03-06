<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Models;

use Laravel\Sanctum\HasApiTokens;

/**
 * \Playground\Test\Models\UserWithSanctum
 *
 * @property int $id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $email_verified_at
 * @property string $name
 * @property string $email
 *
 * NOTE: The model should implement the contract. This class is used to test
 *       when users do not have Contracts\HasApiTokens implemented.
 *
 * @see \Laravel\Sanctum\Contracts\HasApiTokens
 */
class UserWithWithoutSanctumContract extends User
{
    use HasApiTokens;
}
