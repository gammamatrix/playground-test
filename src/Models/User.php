<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Test\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
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
