<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class PlayerActionTest extends BaseTest
{
    use HasGamePlay;
    use HasActionPosts;

    private PlayerAction $playerActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setGamePlay();

        $this->playerActions = $this->container->build(PlayerAction::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanGetTheLatestActionOnANewHand()
    {
        $this->gamePlay->start();
        $this->gameState->setBigBlind();

        $bigBlind = $this->gameState->getBigBlind();
        $latestAction = $this->playerActions->getLatestAction($this->testHand->getId());

        $this->assertEquals($bigBlind['table_seat_id'], $latestAction->getTableSeatId());
    }
}
