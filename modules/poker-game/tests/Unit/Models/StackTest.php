<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class StackTest extends BaseTest
{
    use HasGamePlay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stackModel  = $this->container->get(Stack::class);
        $this->tableModel  = $this->container->get(Table::class);
        $this->playerModel = $this->container->get(Player::class);

        $this->table   = $this->tableModel->create(['name' => 'Test Table', 'seats' => 1]);
        $this->player1 = $this->createPlayer(1);
    }

    /**
     * @test
     * @return void
     */
    public function a_player_can_have_a_stack()
    {
        $stack = $this->stackModel->create([
            'amount' => 1000,
            'table_id' => $this->table->getId(),
            'player_id' => $this->player1->getId()
        ]);

        $this->assertContains($stack->getId(), array_column($this->player1->stacks(), 'id'));
    }
}
