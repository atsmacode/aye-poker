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
            ->setHand()
            ->setGamePlay();
    }

    /**
     * @test
     *
     * @return void
     */
    public function theBigBlindWillBeFirstToActOnTheFlop()
    {
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
        $currentDealer = $this->tableSeats->find([
            'id' => $this->gameState->getSeats()[0]['id'],
        ]);

        $this->gamePlay->start($currentDealer);

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
        $currentDealer = $this->tableSeats->find([
            'id' => $this->gameState->getSeats()[0]['id'],
        ]);

        $this->gamePlay->start($currentDealer);

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
        $currentDealer = $this->tableSeats->find([
            'id' => $this->gameState->getSeats()[1]['id'],
        ]);

        $this->gamePlay->start($currentDealer);

        $response = $this->sitControllerResponseWithPlayerId(playerId: $this->playerOne->getId());

        $this->assertEquals(1, $response['players'][1]['is_dealer']);
    }
}
