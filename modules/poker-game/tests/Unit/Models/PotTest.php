<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Tests\BaseTest;

class PotTest extends BaseTest
{
    private Pot $pot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pot = $this->container->get(Pot::class);
        $this->hand = $this->container->get(Hand::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aHandCanHaveAPot()
    {
        $hand = $this->hand->create(['table_id' => 1]);

        $this->assertEmpty($hand->pot());

        $pot = $this->pot->create([
            'amount' => 75,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals($pot->getId(), $hand->pot()['id']);
    }
}
