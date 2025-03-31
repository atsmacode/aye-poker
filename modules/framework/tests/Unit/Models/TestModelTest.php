<?php

namespace Atsmacode\Framework\Tests;

use Atsmacode\Framework\Models\Test;

class TestModelTest extends BaseTest
{
    private Test $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testModel = $this->container->get(Test::class);
    }

    /** @test */
    public function itCanCreateAndFindRecord()
    {
        $test = $this->testModel->create(['name' => 'Test Name', 'test_desc' => 'Test Description.']);

        $this->assertEquals(
            $test->getId(),
            $this->testModel->find(['id' => $test->getId()])->getId()
        );
    }

    /** @test */
    public function contentCanBeSet()
    {
        $test = $this->testModel->create(['name' => 'Test Name', 'test_desc' => 'Test Description.']);

        $test->setContent([]);

        $this->assertEquals([], $test->getContent());
    }
}
