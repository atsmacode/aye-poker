<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Models\HandType;
use Atsmacode\PokerGame\Tests\BaseTest;

class HandTypeTest extends BaseTest
{
    private HandType $handTypes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handTypes = $this->container->build(HandType::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aHandTypeCanBeCreated()
    {
        $handType = $this->handTypes->create(['name' => 'High Card 24', 'ranking' => 16]);

        $this->assertEquals('High Card 24', $handType->getName());
        $this->assertEquals(16, $handType->getRanking());
    }

    /**
     * @test
     *
     * @return void
     */
    public function aHandTypeCanBeFound()
    {
        $handType = $this->handTypes->find(['name' => 'High Card', 'ranking' => 10]);

        $this->assertEquals('High Card', $handType->getName());
        $this->assertEquals(10, $handType->getRanking());
    }
}
