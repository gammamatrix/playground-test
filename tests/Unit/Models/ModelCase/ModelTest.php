<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\ModelCase;

use Playground\Test\Unit\Models\ModelCase;

/**
 * \Tests\Unit\Playground\Test\Models\ModelCase\ModelTest
 */
class ModelTest extends ModelCase
{
    protected string $modelClass = \Playground\Test\Models\User::class;

    protected bool $hasRelationships = false;
}
