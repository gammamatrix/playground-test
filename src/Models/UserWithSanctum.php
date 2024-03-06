<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Models;

use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
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
 * @see \Illuminate\Foundation\Auth\User
 */
class UserWithSanctum extends User implements HasApiTokensContract
{
    use HasApiTokens;
}
