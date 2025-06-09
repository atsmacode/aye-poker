<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;
use Atsmacode\PokerGame\Tests\HasStreets;

class ThreeHandedActionOptionsTest extends BaseTest
{
    use HasGamePlay;
    use HasActionPosts;
    use HasStreets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand();
    }

    /**
     * @test
     *
     * @return void
     */
    public function theSmallBlindWillBeFirstToActOnTheFlop()
    {
        $this->setGamePlay();

        $this->gamePlay->process($this->gameState);

        $this->givenActionsMeanNewStreetIsDealt();

        $this->setFlop();

        $response = $this->sitControllerResponseWithPlayerId(playerId: $this->playerOne->getId());

        $this->assertEquals(true, $response['players'][2]['action_on']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function whenDealerIsSeatThreeSmallBlindWillBeFirstToActOnTheFlop()
    {
        $this->givenCurrentDealerIs($this->playerTwo->getId())
            ->setGamePlay();

        $this->gamePlay->process($this->gameState);

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
