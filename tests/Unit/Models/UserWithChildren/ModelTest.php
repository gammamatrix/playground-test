<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\UserWithChildren;

use Playground\Test\Models\UserWithChildren;
use Playground\Test\Unit\Models\ModelCase;

/**
 * \Tests\Unit\Playground\Test\Models\UserWithChildren\ModelTest
 */
class ModelTest extends ModelCase
{
    /**
     * @var class-string<UserWithChildren>
     */
    protected string $modelClass = UserWithChildren::class;

    protected bool $hasRelationships = true;

    /**
     * @var array<int, string>
     */
    protected array $hasMany = [
        'children',
    ];

    /**
     * @var array<int, string>
     */
    protected array $hasOne = [
        'parent',
    ];
}
