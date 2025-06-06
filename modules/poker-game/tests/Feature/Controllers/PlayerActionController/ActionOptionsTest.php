<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class ActionOptionsTest extends BaseTest
{
    use HasGamePlay;
    use HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isFourHanded()
            ->setHand()
            ->setGamePlay();
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPlayerFacingARaiseCanFoldCallOrRaise()
    {
        $this->gamePlay->start();

        $request = $this->setPlayerFourRaisesPost();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][1]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][1]['availableOptions']);
        $this->assertContains(Action::CALL, $response['players'][1]['availableOptions']);
        $this->assertContains(Action::RAISE, $response['players'][1]['availableOptions']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPlayerFacingARaiseFoldCanFoldCallOrRaise()
    {
        $this->gamePlay->start();

        $this->givenPlayerFourRaises();

        $request = $this->setPlayerOneFoldsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][2]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][2]['availableOptions']);
        $this->assertContains(Action::CALL, $response['players'][2]['availableOptions']);
        $this->assertContains(Action::RAISE, $response['players'][2]['availableOptions']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aFoldedPlayerHasNoOptions()
    {
        $this->gamePlay->start();

        $request = $this->setPlayerFourFoldsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][1]['action_on']);
        $this->assertEmpty($response['players'][4]['availableOptions']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function theBigBlindFacingACallCanFoldCheckOrRaise()
    {
        $this->gamePlay->start();

        $request = $this->setPlayerTwoCallsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][3]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][3]['availableOptions']);
        $this->assertContains(Action::CHECK, $response['players'][3]['availableOptions']);
        $this->assertContains(Action::RAISE, $response['players'][3]['availableOptions']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function theBigBlindFacingACallFoldCanFoldCheckOrRaise()
    {
        $this->gamePlay->start();

        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        $request = $this->setPlayerTwoFoldsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][3]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][3]['availableOptions']);
        $this->assertContains(Action::CHECK, $response['players'][3]['availableOptions']);
        $this->assertContains(Action::RAISE, $response['players'][3]['availableOptions']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aPlayerFacingACallCanFoldCallOrRaise()
    {
        $this->gamePlay->start();

        $request = $this->setPlayerFourCallsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][1]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][1]['availableOptions']);
        $this->assertContains(Action::CALL, $response['players'][1]['availableOptions']);
        $this->assertContains(Action::RAISE, $response['players'][1]['availableOptions']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function theFirstActivePlayerOnANewStreetCanFoldCheckOrBet()
    {
        $this->gamePlay->start();

        $this->assertCount(1, $this->gameState->updateHandStreets()->getHandStreets());

        $request = $this->givenActionsMeanNewStreetIsDealt();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][3]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][3]['availableOptions']);
        $this->assertContains(Action::CHECK, $response['players'][3]['availableOptions']);
        $this->assertContains(Action::BET, $response['players'][3]['availableOptions']);
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
}
