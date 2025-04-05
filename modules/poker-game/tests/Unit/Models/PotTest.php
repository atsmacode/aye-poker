<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Tests\BaseTest;

class PotTest extends BaseTest
{
    private Pot $pots;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pots = $this->container->get(Pot::class);
        $this->hands = $this->container->get(Hand::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aHandCanHaveAPot()
    {
        $hand = $this->hands->create(['table_id' => 1]);

        $this->assertEmpty($hand->pot());

        $pot = $this->pots->create([
            'amount' => 75,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals($pot->getId(), $hand->pot()['id']);
    }
}
