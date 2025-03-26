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
        $response = $this->gamePlay->start();

        // The small blind was posted
        $this->assertEquals(25, $response['players'][2]['bet_amount']);
        $this->assertEquals('Bet', $response['players'][2]['action_name']);

        // The big blind was posted
        $this->assertEquals(50, $response['players'][3]['bet_amount']);
        $this->assertEquals('Bet', $response['players'][3]['action_name']);

        // The dealer, seat 1, has not acted yet
        $this->assertEquals(null, $response['players'][1]['bet_amount']);
        $this->assertEquals(null, $response['players'][1]['action_id']);

        // Each player in the hand has 2 whole cards
        foreach($response['players'] as $player){
            $this->assertCount(2, $player['whole_cards']);
        }
    }

    /** @test */
    public function itCanDealANewStreet() 
    {
        $this->gamePlay->start();

        $this->executeActionsToContinue();

        $this->gameState->setPlayers();

        $response = $this->gamePlay->play($this->gameState);

        $this->assertCount(2, $this->handStreetModel->find(['hand_id' => $this->gameState->handId()])->getContent());
        $this->assertCount(3, $response['communityCards']);
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
