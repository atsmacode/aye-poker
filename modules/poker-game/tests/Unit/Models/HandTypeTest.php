<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Models\HandType;
use Atsmacode\PokerGame\Tests\BaseTest;

class HandTypeTest extends BaseTest
{
    private HandType $handTypeModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handTypeModel = $this->container->get(HandType::class);
    }

    /**
     * @test
     * @return void
     */
    public function a_hand_type_can_be_created()
    {
        $handType = $this->handTypeModel->create(['name' => 'High Card 24', 'ranking' => 16]);

        $this->assertEquals('High Card 24', $handType->getName());
        $this->assertEquals(16, $handType->getRanking());
    }

    /**
     * @test
     * @return void
     */
    public function a_hand_type_can_be_found()
    {
        $handType = $this->handTypeModel->find(['name' => 'High Card', 'ranking' => 10]);

        $this->assertEquals('High Card', $handType->getName());
        $this->assertEquals(10, $handType->getRanking());
    }

}
