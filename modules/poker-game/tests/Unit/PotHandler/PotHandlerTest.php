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
    protected function setUp(): void
    {
        parent::setUp();

        $this->potHandler  = $this->container->get(PotHandler::class);
        $this->tableModel  = $this->container->get(Table::class);
        $this->playerModel = $this->container->get(Player::class);
        $this->stackModel  = $this->container->get(Stack::class);
        $this->potModel    = $this->container->get(Pot::class);
        $this->handModel   = $this->container->get(Hand::class);
    }

    /**
     * @test
     * @return void
     */
    public function a_pot_can_be_initiated()
    {
        $table = $this->tableModel->create(['name' => 'Test Table', 'seats' => 3]);
        $hand  = $this->handModel->create(['table_id' => $table->getId()]);

        $this->assertNotInstanceOf(Pot::class, $this->potHandler->initiatePot($hand));
    }

    /**
     * @test
     * @return void
     */
    public function a_pot_can_be_awarded_to_a_player()
    {

        $table  = $this->tableModel->create(['name' => 'Test Table', 'seats' => 3]);
        $player = $this->playerModel->create(['name' => 'Player 1']);

        $stack = $this->stackModel->create([
            'amount' => 1000,
            'table_id' => $table->getId(),
            'player_id' => $player->getId()
        ]);

        $hand = $this->handModel->create(['table_id' => $table->getId()]);
        $pot  = $this->potModel->create([
            'amount' => 75,
            'hand_id' => $hand->getId()
        ]);

        $this->assertEquals(1000, $this->stackModel->find(['id' => $stack->getId()])->getAmount());

        $this->potHandler->awardPot($stack->getAmount(), $pot->getAmount(), $player->getId(), $table->getId());

        $this->assertEquals(1075, $this->stackModel->find(['id' => $stack->getId()])->getAmount());
    }
}
