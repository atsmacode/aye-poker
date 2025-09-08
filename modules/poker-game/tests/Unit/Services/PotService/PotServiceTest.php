<?php

namespace Atsmacode\PokerGame\Tests\Unit\Services\PotService;

use Atsmacode\PokerGame\Enums\GameMode;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Services\Pots\PotService;
use Atsmacode\PokerGame\Tests\BaseTest;

class PotServiceTest extends BaseTest
{
    private PotService $potService;
    private Stack $stacks;
    private Pot $pots;

    protected function setUp(): void
    {
        parent::setUp();

        $this->potService = $this->container->get(PotService::class);
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
    public function aPotCanBeInitiated()
    {
        $table = $this->tables->create(['name' => 'Test Table', 'seats' => 3]);

        $game = $this->games->create([
            'table_id' => $table->getId(),
            'mode' => GameMode::REAL->value,
        ]);

        $hand = $this->hands->create(['game_id' => $game->getId()]);

        $this->assertNotInstanceOf(Pot::class, $this->potService->initiatePot($hand));
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

        $game = $this->games->create([
            'table_id' => $table->getId(),
            'mode' => GameMode::REAL->value,
        ]);

        $hand = $this->hands->create(['game_id' => $game->getId()]);

        $pot = $this->pots->create([
            'amount' => 75,
            'hand_id' => $hand->getId(),
        ]);

        $this->assertEquals(1000, $this->stacks->find(['id' => $stack->getId()])->getAmount());

        $this->potService->awardPot($stack->getAmount(), $pot->getAmount(), $player->getId(), $table->getId());

        $this->assertEquals(1075, $this->stacks->find(['id' => $stack->getId()])->getAmount());
    }
}
