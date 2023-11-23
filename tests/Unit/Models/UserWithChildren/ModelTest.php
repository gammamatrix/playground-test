<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Unit\GammaMatrix\Playground\Test\Models\UserWithChildren;

use GammaMatrix\Playground\Test\Unit\Models\ModelCase;

/**
 * \Tests\Unit\GammaMatrix\Playground\Test\Models\UserWithChildren\ModelTest
 *
 */
class ModelTest extends ModelCase
{
    protected string $modelClass = \GammaMatrix\Playground\Test\Models\UserWithChildren::class;

    protected bool $hasRelationships = true;

    protected array $hasMany = [
        'children',
    ];

    protected array $hasOne = [
        'parent',
    ];
}
