<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Test\Models;

use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Support\Carbon;

/**
 * \Playground\Test\Models\AbstractUser
 *
 * @see Illuminate\Foundation\Auth\User
 *
 * @property int $id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $email_verified_at
 * @property string $name
 * @property string $email
 */
abstract class AbstractUser extends BaseUser
{
    protected $attributes = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'users';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
