<?php

namespace Atsmacode\PokerGame\Tests\Unit\PotHandler;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\PotHandler\PotHandler;
use Atsmacode\PokerGame\Tests\BaseTest;

class PotHandlerTest extends BaseTest
{
    private PotHandler $potHandler;
    private Stack $stack;
    private Pot $pot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->potHandler = $this->container->get(PotHandler::class);
        $this->table = $this->container->get(Table::class);
        $this->player = $this->container->get(Player::class);
        $this->stack = $this->container->get(Stack::class);
        $this->pot = $this->container->get(Pot::class);
        $this->hand = $this->container->get(Hand::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPotCanBeInitiated()
    {
        $table = $this->table->create(['name' => 'Test Table', 'seats' => 3]);
        $hand = $this->hand->create(['table_id' => $table->getId()]);

        $this->assertNotInstanceOf(Pot::class, $this->potHandler->initiatePot($hand));
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPotCanBeAwardedToAPlayer()
    {
        $table = $this->table->create(['name' => 'Test Table', 'seats' => 3]);
        $player = $this->player->create(['name' => $this->fake->unique()->name()]);

        $stack = $this->stack->create([
            'amount' => 1000,
            'table_id' => $table->getId(),
            'player_id' => $player->getId(),
        ]);

        $hand = $this->hand->create(['table_id' => $table->getId()]);
        $pot = $this->pot->create([
            'amount' => 75,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals(1000, $this->stack->find(['id' => $stack->getId()])->getAmount());

        $this->potHandler->awardPot($stack->getAmount(), $pot->getAmount(), $player->getId(), $table->getId());

        $this->assertEquals(1075, $this->stack->find(['id' => $stack->getId()])->getAmount());
    }
}
