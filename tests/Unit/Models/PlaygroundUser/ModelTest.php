<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\PlaygroundUser;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\PlaygroundUser;
use Playground\Test\Unit\Models\ModelCase;

/**
 * \Tests\Unit\Playground\Test\Models\PlaygroundUser\ModelTest
 */
#[CoversClass(PlaygroundUser::class)]
class ModelTest extends ModelCase
{
    protected string $modelClass = PlaygroundUser::class;

    protected bool $hasRelationships = false;
}
