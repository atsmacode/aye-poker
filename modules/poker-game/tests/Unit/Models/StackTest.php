<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class StackTest extends BaseTest
{
    use HasHandFlow;

    private Stack $stacks;
    private Player $player1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stacks = $this->container->build(Stack::class);
        $this->tables = $this->container->build(Table::class);
        $this->players = $this->container->build(Player::class);

        $this->testTable = $this->tables->create(['name' => 'Test Table', 'seats' => 1]);
        $this->player1 = $this->createPlayer();
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPlayerCanHaveAStack()
    {
        $stack = $this->stacks->create([
            'amount' => 1000,
            'table_id' => $this->testTable->getId(),
            'player_id' => $this->player1->getId(),
        ]);

        $this->assertContains($stack->getId(), array_column($this->player1->stacks(), 'id'));
    }
}
