<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;
use Atsmacode\PokerGame\Tests\HasStreets;

class HeadsUpActionOptionsTest extends BaseTest
{
    use HasHandFlow;
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
        $this->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->setFlop();

        $response = $this->sitControllerResponse();

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
            ->setHandFlow();

        $this->handFlow->process($this->gameState);

        $response = $this->sitControllerResponse();

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
            ->setHandFlow();

        $this->handFlow->process($this->gameState);

        $response = $this->sitControllerResponse();

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
            ->setHandFlow();

        $this->handFlow->process($this->gameState);

        $response = $this->sitControllerResponse();

        $this->assertEquals(1, $response['players'][1]['is_dealer']);
    }
}
