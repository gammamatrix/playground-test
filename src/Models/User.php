<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

/**
 * \Playground\Test\Models\User
 *
 * @see \Illuminate\Foundation\Auth\User
 */
class User extends AbstractUser implements CanResetPasswordContract, MustVerifyEmailContract
{
    use CanResetPassword;
    use MustVerifyEmail;

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
