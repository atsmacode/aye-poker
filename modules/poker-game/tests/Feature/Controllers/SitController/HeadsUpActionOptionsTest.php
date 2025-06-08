<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;
use Atsmacode\PokerGame\Tests\HasStreets;

class HeadsUpActionOptionsTest extends BaseTest
{
    use HasGamePlay;
    use HasActionPosts;
    use HasStreets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isHeadsUp()
            ->setHand();
    }

    /**
     * @test
     *
     * @return void
     */
    public function theBigBlindWillBeFirstToActOnTheFlop()
    {
        $this->setGamePlay();

        $this->gamePlay->start();

        $this->setFlop();

        $response = $this->sitControllerResponseWithPlayerId(playerId: $this->playerOne->getId());

        $this->assertEquals(true, $response['players'][2]['action_on']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function whenCurrentDealerIsPlayerOnePlayerTwoWillBeTheNewDealer()
    {
        $this->givenCurrentDealerIs($this->playerOne->getId())
            ->setGamePlay();

        $this->gamePlay->start();

        $response = $this->sitControllerResponseWithPlayerId(playerId: $this->playerOne->getId());

        $this->assertEquals(1, $response['players'][2]['is_dealer']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function whenCurrentDealerIsPlayerOnePlayerTwoWillBeTheNewSmallBlind()
    {
        $this->givenCurrentDealerIs($this->playerOne->getId())
            ->setGamePlay();

        $this->gamePlay->start();

        $response = $this->sitControllerResponseWithPlayerId(playerId: $this->playerOne->getId());

        $this->assertEquals(1, $response['players'][2]['small_blind']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function whenCurrentDealerIsPlayerTwoPlayerOneWillBeTheNewDealer()
    {
        $this->givenCurrentDealerIs($this->playerTwo->getId())
            ->setGamePlay();

        $this->gamePlay->start();

        $response = $this->sitControllerResponseWithPlayerId(playerId: $this->playerOne->getId());

        $this->assertEquals(1, $response['players'][1]['is_dealer']);
    }
}
