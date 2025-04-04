<?php

namespace Atsmacode\PokerGame\Tests\Unit\BetHandler;

use Atsmacode\PokerGame\BetHandler\BetHandler;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Tests\BaseTest;

class BetHandlerTest extends BaseTest
{
    private BetHandler $betHandler;
    private Pot $pot;
    private Stack $stack;

    protected function setUp(): void
    {
        parent::setUp();

        $this->betHandler = $this->container->get(BetHandler::class);
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
    public function aBetAmountIsAddedToThePotAndSubtractedFromThePlayerStack()
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
            'amount' => 0,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals(1000, $this->stack->find(['id' => $stack->getId()])->getAmount());

        $this->betHandler->handle($hand, $stack->getAmount(), $player->getId(), $table->getId(), 150);

        $this->assertEquals(150, $this->pot->find(['id' => $pot->getId()])->getAmount());
        $this->assertEquals(850, $this->stack->find(['id' => $stack->getId()])->getAmount());
    }
}
