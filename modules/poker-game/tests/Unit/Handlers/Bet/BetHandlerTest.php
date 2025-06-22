<?php

namespace Atsmacode\PokerGame\Tests\Unit\Handlers\Bet;

use Atsmacode\PokerGame\Enums\GameMode;
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
        $this->tables = $this->container->build(Table::class);
        $this->players = $this->container->build(Player::class);
        $this->stacks = $this->container->build(Stack::class);
        $this->pots = $this->container->build(Pot::class);
        $this->hands = $this->container->build(Hand::class);
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

        $game = $this->games->create([
            'table_id' => $table->getId(),
            'mode' => GameMode::REAL->value,
        ]);

        $hand = $this->hands->create(['game_id' => $game->getId()]);
        $pot = $this->pots->create([
            'amount' => 0,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals(1000, $this->stacks->find(['id' => $stack->getId()])->getAmount());

        $this->betHandler->handle($hand->getId(), $stack->getAmount(), $player->getId(), $table->getId(), 150);

        $this->assertEquals(150, $this->pots->find(['id' => $pot->getId()])->getAmount());
        $this->assertEquals(850, $this->stacks->find(['id' => $stack->getId()])->getAmount());
    }
}
