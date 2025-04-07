<?php

namespace Atsmacode\Framework\Tests;

use Atsmacode\Framework\Models\Test;

class TestModelTest extends BaseTest
{
    private Test $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->test = $this->container->build(Test::class);
    }

    /** @test */
    public function itCanCreateAndFindRecord()
    {
        $test = $this->test->create(['name' => 'Test Name', 'test_desc' => 'Test Description.']);

        $this->assertEquals(
            $test->getId(),
            $this->test->find(['id' => $test->getId()])->getId()
        );
    }

    /** @test */
    public function contentCanBeSet()
    {
        $test = $this->test->create(['name' => 'Test Name', 'test_desc' => 'Test Description.']);

        $test->setContent([]);

        $this->assertEquals([], $test->getContent());
    }
}
