<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController\FourHanded;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class PlayerActionControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isFourHanded()
            ->setHand()
            ->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function it_adds_a_player_that_calls_the_big_blind_to_the_list_of_table_seats_that_can_continue()
    {
        $this->gamePlay->start();

        $request  = $this->setPlayerFourCallsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals(1, $response['players'][4]['can_continue']);
    }

    /**
     * @test
     * @return void
     */
    public function it_removes_a_folded_player_from_the_list_of_seats_that_can_continue()
    {
        $this->gamePlay->start();

        $this->givenBigBlindRaisesPreFlopCaller();
        $this->givenPlayerThreeCanContinue();

        $request  = $this->setPlayerFourFoldsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals(0, $response['players'][4]['can_continue']);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_deal_a_new_street()
    {
        $this->gamePlay->start();

        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());

        $request = $this->givenActionsMeanNewStreetIsDealt();

        $this->actionControllerResponse($request);

        $this->assertCount(2, $this->handStreetModel->find(['hand_id' => $this->gameState->handId()])->getContent());
    }

    /**
     * @test
     * @return void
     */
    public function the_big_blind_will_win_the_pot_if_all_other_players_fold_pre_flop()
    {
        $this->gamePlay->start();

        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());

        $this->givenPlayerFourFolds();
        $this->givenPlayerFourCanNotContinue();

        $this->givenPlayerOneFolds();
        $this->givenPlayerOneCanNotContinue();

        $request  = $this->setPlayerTwoFoldsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());
        $this->assertEquals(1, $response['players'][3]['can_continue']);
        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
    }

    /**
     * @test
     * @return void
     */
    public function the_pre_flop_action_will_be_back_on_the_big_blind_caller_if_the_big_blind_raises()
    {
        $this->gamePlay->start();

        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());

        $request  = $this->givenBigBlindRaisesPreFlopCaller();
        $response = $this->actionControllerResponse($request);

        // We are still on the pre-flop action
        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());

        $this->assertTrue($response['players'][4]['action_on']);
    }

    /**
     * @test
     * @return void
     */
    public function if_the_dealer_is_seat_two_and_the_first_active_seat_on_a_new_street_the_first_active_seat_after_them_will_be_first_to_act()
    {
        $this->gamePlay->start($this->tableSeatModel->find([
            'id' => $this->gameState->getSeats()[0]['id']
        ]));

        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());

        $request  = $this->givenActionsMeanNewStreetIsDealtWhenDealerIsSeatTwo();
        $response = $this->actionControllerResponse($request);

        $this->assertCount(2, $this->handStreetModel->find(['hand_id' => $this->gameState->handId()])->getContent());

        $this->assertTrue($response['players'][3]['action_on']);
    }

    /**
     * @test
     * @return void
     */
    public function if_there_is_one_seat_after_current_dealer_big_blind_will_be_seat_two()
    {
        $this->gamePlay->start($this->tableSeatModel->find([
            'id' => $this->gameState->getSeats()[2]['id']
        ]));

        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());

        $request  = $this->setPost();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals(1, $response['players'][1]['small_blind']);
        $this->assertEquals(1, $response['players'][2]['big_blind']);
    }

    /**
     * @test
     * @return void
     */
    public function if_the_dealer_is_the_first_active_seat_on_a_new_street_the_first_active_seat_after_them_will_be_first_to_act()
    {
        $this->gamePlay->start();

        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());

        $request  = $this->givenActionsMeanNewStreetIsDealt();
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
