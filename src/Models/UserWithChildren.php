<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * \Playground\Test\Models\UserWithChildren
 */
class UserWithChildren extends User
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
        'parent_id',
    ];

    public $timestamps = false;

    protected $table = 'users';

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'name' => '',
        'email' => '',
        'parent_id' => null,
    ];

    public function children(): HasMany
    {
        return $this->hasMany(self::class);
    }

    public function parent(): HasOne
    {
        return $this->hasOne(
            self::class,
            'id',
            'parent_id'
        );
    }
}
