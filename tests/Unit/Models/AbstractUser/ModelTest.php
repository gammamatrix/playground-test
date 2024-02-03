<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\AbstractUser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Playground\Test\Models\AbstractUser;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Models\Model\ModelTest
 */
#[CoversClass(AbstractUser::class)]
class ModelTest extends TestCase
{
    public function test_getAttributes(): void
    {
        $expected = [
            'name' => '',
            'email' => '',
        ];

        /**
         * @var \Playground\Test\Models\AbstractUser&MockObject
         */
        $mock = $this->getMockForAbstractClass(AbstractUser::class);

        $attributes = $mock->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }
}
