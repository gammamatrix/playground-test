<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/**
 * \GammaMatrix\Playground\Test\Models\AbstractUser
 *
 * @see Illuminate\Foundation\Auth\User
 */
abstract class AbstractUser extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
    ];

    public $timestamps = false;

    protected $table = 'users';

    protected $attributes = [
        'name' => '',
        'email' => '',
        // 'role' => '',
    ];
}
