<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

/**
 * \Playground\Test\Models\AbstractUser
 *
 * @see Illuminate\Foundation\Auth\User
 */
abstract class AbstractUser extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasFactory;
    use Notifiable;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'name' => '',
        'email' => '',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
    ];

    public $timestamps = false;

    protected $table = 'users';
}
