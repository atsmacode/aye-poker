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
    private Stack $stacks;
    private Pot $pots;

    protected function setUp(): void
    {
        parent::setUp();

        $this->potHandler = $this->container->get(PotHandler::class);
        $this->tables = $this->container->get(Table::class);
        $this->players = $this->container->get(Player::class);
        $this->stacks = $this->container->get(Stack::class);
        $this->pots = $this->container->get(Pot::class);
        $this->hands = $this->container->get(Hand::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPotCanBeInitiated()
    {
        $table = $this->tables->create(['name' => 'Test Table', 'seats' => 3]);
        $hand = $this->hands->create(['table_id' => $table->getId()]);

        $this->assertNotInstanceOf(Pot::class, $this->potHandler->initiatePot($hand));
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPotCanBeAwardedToAPlayer()
    {
        $table = $this->tables->create(['name' => 'Test Table', 'seats' => 3]);
        $player = $this->players->create(['name' => $this->fake->unique()->name()]);

        $stack = $this->stacks->create([
            'amount' => 1000,
            'table_id' => $table->getId(),
            'player_id' => $player->getId(),
        ]);

        $hand = $this->hands->create(['table_id' => $table->getId()]);
        $pot = $this->pots->create([
            'amount' => 75,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals(1000, $this->stacks->find(['id' => $stack->getId()])->getAmount());

        $this->potHandler->awardPot($stack->getAmount(), $pot->getAmount(), $player->getId(), $table->getId());

        $this->assertEquals(1075, $this->stacks->find(['id' => $stack->getId()])->getAmount());
    }
}
