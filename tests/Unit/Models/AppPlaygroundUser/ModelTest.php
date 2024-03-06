<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\AppPlaygroundUser;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Test\Models\AppPlaygroundUser;
use Playground\Test\Unit\Models\ModelCase;

/**
 * \Tests\Unit\Playground\Test\Models\AppPlaygroundUser\ModelTest
 */
#[CoversClass(AppPlaygroundUser::class)]
class ModelTest extends ModelCase
{
    protected string $modelClass = AppPlaygroundUser::class;

    protected bool $hasRelationships = true;

    protected array $hasOne = [
        'creator',
        'modifier',
    ];
}
