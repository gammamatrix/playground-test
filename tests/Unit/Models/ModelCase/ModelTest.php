<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\ModelCase;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\User;
use Playground\Test\Unit\Models\ModelCase;

/**
 * \Tests\Unit\Playground\Test\Models\ModelCase\ModelTest
 */
#[CoversClass(User::class)]
class ModelTest extends ModelCase
{
    /**
     * @var class-string<User>
     */
    protected string $modelClass = User::class;

    protected bool $hasRelationships = false;
}
