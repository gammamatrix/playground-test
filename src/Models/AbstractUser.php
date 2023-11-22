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

    protected $fillable = ['email'];

    public $timestamps = false;

    protected $table = 'users';

    protected $attributes = [
        // 'role' => '',
    ];
}
