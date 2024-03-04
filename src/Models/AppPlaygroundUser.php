<?php
/**
 * Playground
 */
namespace Playground\Test\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum;
use Playground\Models\Interfaces\WithCreatorInterface;
use Playground\Models\Interfaces\WithModifierInterface;
use Playground\Models\Traits;
use Playground\Models\Traits\WithCreator;
use Playground\Models\Traits\WithModifier;
use Playground\Models\User as BaseUser;

/**
 * \Playground\Test\Models\AppPlaygroundUser
 *
 * This model includes all possible and/or compatible Playground features.
 */
class AppPlaygroundUser extends BaseUser implements MustVerifyEmail, Sanctum\Contracts\HasApiTokens, WithCreatorInterface, WithModifierInterface
{
    use Notifiable;
    use Sanctum\HasApiTokens;
    use SoftDeletes;
    use Traits\ScopeFilterColumns;
    use Traits\ScopeFilterDates;
    use Traits\ScopeFilterFlags;
    use Traits\ScopeFilterIds;
    use Traits\ScopeFilterTrash;
    use Traits\ScopeSort;
    use WithCreator;
    use WithModifier;

    protected $table = 'users';
}
