<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController\ActionOptions\SixHanded;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;
use Atsmacode\PokerGame\Tests\HasStreets;
use Atsmacode\PokerGame\Models\PlayerAction;

class PlayerActionControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts, HasStreets;

    private PlayerAction $playerActionModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->playerActionModel  = $this->container->build(PlayerAction::class);

        $this->isSixHanded()
            ->setHand()
            ->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function aPlayerFacingAPreviousRaiseCanFoldCallOrRaise()
    {
        $this->gamePlay->start();

        $this->setFlop();

        $this->updateActionsOnNewStreet();

        $this->givenPlayerFourRaises();
        $this->givenPlayerFourCanContinue();

        $this->givenPlayerFiveCalls();
        $this->givenPlayerFiveCanContinue();

        $request  = $this->setPlayerSixFoldsPost(streetNumber: 1);
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][1]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][1]['availableOptions']);
        $this->assertContains(Action::CALL, $response['players'][1]['availableOptions']);
        $this->assertContains(Action::RAISE, $response['players'][1]['availableOptions']);
    }

    /**
     * @test
     * @return void
     */
    public function theBigBlindCanFoldCheckOrRaiseIfDealerCallsAndSmallBlindFolds()
    {
        $this->gamePlay->start();

        $this->givenPlayerFourFolds();
        $this->givenPlayerFourCanNotContinue();

        $this->givenPlayerFiveFolds();
        $this->givenPlayerFiveCanNotContinue();

        $this->givenPlayerSixFolds();
        $this->givenPlayerSixCanNotContinue();

        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        $request  = $this->setPlayerTwoFoldsPost();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][3]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][3]['availableOptions']);
        $this->assertContains(Action::CHECK, $response['players'][3]['availableOptions']);
        $this->assertContains(Action::RAISE, $response['players'][3]['availableOptions']);
    }

    private function updateActionsOnNewStreet(): void
    {
        $this->gameState->updateHandStreets();

        $handStreets  = $this->gameState->getHandStreets();
        $latestStreet = array_pop($handStreets);

        $this->playerActionModel->find(['hand_id' => $this->gameState->handId()])
            ->updateBatch([
                'action_id'      => null,
                'hand_street_id' => $latestStreet['id']
            ], 'hand_id = ' . $this->gameState->handId());
    }
}
