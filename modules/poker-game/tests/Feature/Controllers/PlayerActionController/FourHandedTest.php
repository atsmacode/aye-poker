<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class FourHandedTest extends BaseTest
{
    use HasHandFlow;
    use HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isFourHanded()
            ->setHand();
    }

    /**
     * @test
     *
     * @return void
     */
    public function itAddsAPlayerThatCallsTheBigBlindToTheListOfTableSeatsThatCanContinue()
    {
        $this->setHandFlow();

        $this->handFlow->process($this->gameState);

        $request = $this->setPlayerFourCallsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals(1, $response['players'][4]['can_continue']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itRemovesAFoldedPlayerFromTheListOfSeatsThatCanContinue()
    {
        $this->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->givenBigBlindRaisesPreFlopCaller();
        $this->givenPlayerThreeCanContinue();

        $request = $this->setPlayerFourFoldsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals(0, $response['players'][4]['can_continue']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDealANewStreet()
    {
        $this->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->assertCount(1, $this->gameState->loadHandStreets()->getHandStreets());

        $request = $this->givenActionsMeanNewStreetIsDealt();

        $this->actionControllerResponse($request);

        $this->assertCount(2, $this->handStreets->find(['hand_id' => $this->gameState->handId()])->getContent());
    }

    /**
     * @test
     *
     * @return void
     */
    public function theBigBlindWillWinThePotIfAllOtherPlayersFoldPreFlop()
    {
        $this->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->assertCount(1, $this->gameState->loadHandStreets()->getHandStreets());

        $this->givenPlayerFourFolds();
        $this->givenPlayerFourCanNotContinue();

        $this->givenPlayerOneFolds();
        $this->givenPlayerOneCanNotContinue();

        $request = $this->setPlayerTwoFoldsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertCount(1, $this->gameState->loadHandStreets()->getHandStreets());
        $this->assertEquals(1, $response['players'][3]['can_continue']);
        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function thePreFlopActionWillBeBackOnTheBigBlindCallerIfTheBigBlindRaises()
    {
        $this->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->assertCount(1, $this->gameState->loadHandStreets()->getHandStreets());

        $request = $this->givenBigBlindRaisesPreFlopCaller();
        $response = $this->actionControllerResponse($request);

        // We are still on the pre-flop action
        $this->assertCount(1, $this->gameState->loadHandStreets()->getHandStreets());

        $this->assertTrue($response['players'][4]['action_on']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function ifTheDealerIsSeatTwoAndTheFirstActiveSeatOnANewStreetTheFirstActiveSeatAfterThemWillBeFirstToAct()
    {
        $this->givenCurrentDealerIs($this->playerOne->getId())
            ->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->assertCount(1, $this->gameState->loadHandStreets()->getHandStreets());

        $request = $this->givenActionsMeanNewStreetIsDealtWhenDealerIsSeatTwo();
        $response = $this->actionControllerResponse($request);

        $this->assertCount(2, $this->handStreets->find(['hand_id' => $this->gameState->handId()])->getContent());

        $this->assertTrue($response['players'][3]['action_on']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function ifThereIsOneSeatAfterCurrentDealerBigBlindWillBeSeatTwo()
    {
        $this->givenCurrentDealerIs($this->playerThree->getId())
            ->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->assertCount(1, $this->gameState->loadHandStreets()->getHandStreets());

        $request = $this->setPost();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals(1, $response['players'][1]['small_blind']);
        $this->assertEquals(1, $response['players'][2]['big_blind']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function ifTheDealerIsTheFirstActiveSeatOnANewStreetTheFirstActiveSeatAfterThemWillBeFirstToAct()
    {
        $this->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->assertCount(1, $this->gameState->loadHandStreets()->getHandStreets());

        $request = $this->givenActionsMeanNewStreetIsDealt();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][3]['action_on']);
    }

    private function givenBigBlindRaisesPreFlopCaller()
    {
        $this->givenPlayerFourCalls();
        $this->givenPlayerFourCanContinue();

        $this->givenPlayerOneFolds();
        $this->givenPlayerOneCanNotContinue();

        $this->givenPlayerTwoFolds();
        $this->givenPlayerTwoCanNotContinue();

        return $this->setPlayerThreeRaisesPost();
    }

    private function givenActionsMeanNewStreetIsDealt()
    {
        $this->givenPlayerFourCalls();
        $this->givenPlayerFourCanContinue();

        $this->givenPlayerOneFolds();
        $this->givenPlayerOneCanNotContinue();

        $this->givenPlayerTwoFolds();
        $this->givenPlayerTwoCanNotContinue();

        return $this->setPlayerThreeChecksPost();
    }

    private function givenActionsMeanNewStreetIsDealtWhenDealerIsSeatTwo()
    {
        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        $this->givenPlayerTwoCalls();
        $this->givenPlayerTwoCanContinue();

        $this->givenPlayerThreeCallsSmallBlind();
        $this->givenPlayerThreeCanContinue();

        return $this->setPlayerFourChecksPost();
    }
}
