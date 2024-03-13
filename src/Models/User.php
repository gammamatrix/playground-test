<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Models;

use Illuminate\Support\Carbon;

/**
 * \Playground\Test\Models\User
 *
 * @property int $id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $email_verified_at
 * @property string $name
 * @property string $email
 * @property string $password
 * @property ?string $remember_token
 *
 * @see \Illuminate\Foundation\Auth\User
 */
class User extends DefaultUser
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
    ];
}
