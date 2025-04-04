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

    private Stack $stack;
    private Player $player1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stack = $this->container->get(Stack::class);
        $this->table = $this->container->get(Table::class);
        $this->player = $this->container->get(Player::class);

        $this->testTable = $this->table->create(['name' => 'Test Table', 'seats' => 1]);
        $this->player1 = $this->createPlayer(1);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPlayerCanHaveAStack()
    {
        $stack = $this->stack->create([
            'amount' => 1000,
            'table_id' => $this->testTable->getId(),
            'player_id' => $this->player1->getId(),
        ]);

        $this->assertContains($stack->getId(), array_column($this->player1->stacks(), 'id'));
    }
}
