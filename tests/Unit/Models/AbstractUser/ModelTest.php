<?php
/**
 * Playground
 */
namespace Tests\Unit\Playground\Test\Models\AbstractUser;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\Unit\Playground\Test\TestCase;

/**
 * \Tests\Unit\Playground\Models\Model\ModelTest
 */
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
        $mock = $this->getMockForAbstractClass(\Playground\Test\Models\AbstractUser::class);

        $attributes = $mock->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }
}
