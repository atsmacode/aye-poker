<?php

namespace Atsmacode\PokerGame\Tests\Unit\Handlers\Bet;

use Atsmacode\PokerGame\Handlers\Bet\BetHandler;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Tests\BaseTest;

class BetHandlerTest extends BaseTest
{
    private BetHandler $betHandler;
    private Pot $pots;
    private Stack $stacks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->betHandler = $this->container->get(BetHandler::class);
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
    public function aBetAmountIsAddedToThePotAndSubtractedFromThePlayerStack()
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
            'amount' => 0,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals(1000, $this->stacks->find(['id' => $stack->getId()])->getAmount());

        $this->betHandler->handle($hand, $stack->getAmount(), $player->getId(), $table->getId(), 150);

        $this->assertEquals(150, $this->pots->find(['id' => $pot->getId()])->getAmount());
        $this->assertEquals(850, $this->stacks->find(['id' => $stack->getId()])->getAmount());
    }
}
