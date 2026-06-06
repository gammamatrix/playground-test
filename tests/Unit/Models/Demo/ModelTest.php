<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Test\Models\Demo;

use Playground\Test\Models\Demo;
use Tests\Unit\Playground\Test\Models\ModelCase;

/**
 * \Tests\Unit\Playground\Test\Models\Demo\ModelTest
 */
class ModelTest extends ModelCase
{
    protected string $modelClass = Demo::class;

    protected bool $hasRelationships = true;

    /**
     * @var array<int, string> Test has one relationships.
     */
    protected array $hasMany = [
        'widgets',
    ];

    /**
     * @var array<int, string> Test has one relationships.
     */
    protected array $hasOne = [
        'creator',
        'modifier',
        'owner',
        'parent',
    ];
}
