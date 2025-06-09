<?php

namespace Atsmacode\PokerGame\Tests\Unit\GamePlay;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class GamePlayTest extends BaseTest
{
    use HasGamePlay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setGamePlay();
    }

    /** @test */
    public function itCanStartAGame()
    {
        $gameState = $this->gamePlay->start();

        $players = $gameState->getPlayerState();

        // The small blind was posted
        $this->assertEquals(25, $players[2]['bet_amount']);
        $this->assertEquals('Bet', $players[2]['action_name']);

        // The big blind was posted
        $this->assertEquals(50, $players[3]['bet_amount']);
        $this->assertEquals('Bet', $players[3]['action_name']);

        // The dealer, seat 1, has not acted yet
        $this->assertEquals(null, $players[1]['bet_amount']);
        $this->assertEquals(null, $players[1]['action_id']);

        // Each player in the hand has 2 whole cards
        foreach ($players as $player) {
            $this->assertCount(2, $player['whole_cards']);
        }
    }

    /** @test */
    public function itCanDealANewStreet()
    {
        $this->gamePlay->start();

        $this->executeActionsToContinue();

        $this->gameState->setPlayers();

        $this->gameState->setHandIsActive(true);

        $gameState = $this->gamePlay->play($this->gameState);

        $this->assertCount(2, $gameState->getHandStreets());
        $this->assertCount(3, $gameState->getCommunityCards());
    }

    protected function executeActionsToContinue()
    {
        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        $this->givenPlayerTwoFolds();
        $this->givenPlayerTwoCanNotContinue();

        $this->givenPlayerThreeChecks();
        $this->givenPlayerThreeCanContinue();
    }
}
