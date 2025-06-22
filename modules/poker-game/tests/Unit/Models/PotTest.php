<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Enums\GameMode;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Tests\BaseTest;

class PotTest extends BaseTest
{
    private Pot $pots;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pots = $this->container->build(Pot::class);
        $this->hands = $this->container->build(Hand::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aHandCanHaveAPot()
    {
        $table = $this->tables->create(['name' => 'Test Table', 'seats' => 3]);
        $game = $this->games->create([
            'table_id' => $table->getId(),
            'mode' => GameMode::REAL->value,
        ]);

        $hand = $this->hands->create(['game_id' => $game->getId()]);

        $this->assertEmpty($hand->pot());

        $pot = $this->pots->create([
            'amount' => 75,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals($pot->getId(), $hand->pot()['id']);
    }
}
