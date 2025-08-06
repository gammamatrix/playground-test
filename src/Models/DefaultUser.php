<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Test\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\Playground\Test\Models\DefaultUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * \Playground\Test\Models\DefaultUser
 *
 * NOTE: The default user should match what is installed by Laravel.
 *
 * @see Authenticatable
 */
class DefaultUser extends Authenticatable
{
    /** @use HasFactory<DefaultUserFactory> */
    use HasFactory;

    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
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
