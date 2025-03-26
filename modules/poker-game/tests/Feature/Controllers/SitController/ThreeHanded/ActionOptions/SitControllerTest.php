<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController\ThreeHanded\ActionOptions;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;
use Atsmacode\PokerGame\Tests\HasStreets;

class SitControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts, HasStreets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function theSmallBlindWillBeFirstToActOnTheFlop()
    {
        $this->gamePlay->start();

        $this->givenActionsMeanNewStreetIsDealt();

        $this->setFlop();

        $response = $this->sitControllerResponseWithPlayerId(playerId: $this->playerOne->getId());

        $this->assertEquals(true, $response['players'][2]['action_on']);
    }

    /**
     * @test
     * @return void
     */
    public function whenDealerIsSeatThreeSmallBlindWillBeFirstToActOnTheFlop()
    {
        $currentDealer = $this->tableSeatModel->find([
            'id' => $this->gameState->getSeats()[1]['id']
        ]);
        
        $this->gamePlay->start($currentDealer);

        $this->givenActionsMeanNewStreetIsDealt();

        $this->setFlop();

        $response = $this->sitControllerResponseWithPlayerId(playerId: $this->playerOne->getId());

        $this->assertEquals(true, $response['players'][1]['action_on']);
    }

    private function givenActionsMeanNewStreetIsDealt()
    {
        $this->givenPlayerThreePreviouslyCalled();
        $this->givenPlayerThreeCanNotContinue();

        $this->givenPlayerOnePreviouslyCalled();
        $this->givenPlayerOneCanNotContinue();

        $this->givenPlayerTwoPreviouslyChecked();
        $this->givenPlayerTwoCanNotContinue();
    }
}
