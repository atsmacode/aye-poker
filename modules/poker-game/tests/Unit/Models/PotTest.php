<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Tests\BaseTest;

class PotTest extends BaseTest
{
    private Pot $potModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->potModel  = $this->container->get(Pot::class);
        $this->handModel = $this->container->get(Hand::class);
    }

    /**
     * @test
     * @return void
     */
    public function a_hand_can_have_a_pot()
    {
        $hand = $this->handModel->create(['table_id' => 1]);

        $this->assertEmpty($hand->pot());

        $pot = $this->potModel->create([
            'amount' => 75,
            'hand_id' => $hand->getId()
        ]);

        $this->assertEquals($pot->getId(), $hand->pot()['id']);
    }
}
