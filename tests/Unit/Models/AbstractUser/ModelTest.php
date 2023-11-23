<?php
/**
 * GammaMatrix
 *
 */

namespace Tests\Unit\GammaMatrix\Playground\Test\Models\AbstractUser;

use Tests\Unit\GammaMatrix\Playground\Test\TestCase;

/**
 * \Tests\Unit\GammaMatrix\Playground\Models\Model\ModelTest
 *
 */
class ModelTest extends TestCase
{
    /**
     * @var string
     */
    public const ABSTRACT_CLASS = \GammaMatrix\Playground\Test\Models\AbstractUser::class;

    /**
     * @var object
     */
    public $mock;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!class_exists(static::ABSTRACT_CLASS)) {
            $this->markTestSkipped(sprintf(
                'Expecting the abstract model class to exist: %1$s',
                static::ABSTRACT_CLASS
            ));
        }

        $this->mock = $this->getMockForAbstractClass(static::ABSTRACT_CLASS);
    }

    public function test_getAttributes()
    {
        $expected = [
            'name' => '',
            'email' => '',
        ];

        $attributes = $this->mock->getAttributes();

        $this->assertIsArray($attributes);

        $this->assertSame($expected, $attributes);
    }
}
