<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController\ActionOptions\HeadsUp;

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

        $this->isHeadsUp()
            ->setHand()
            ->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function thePlayerFirstToActCanFoldCheckOrBet()
    {
        $this->gamePlay->start();

        $request  = $this->givenActionsMeanNewStreetIsDealt();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][2]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][2]['availableOptions']);
        $this->assertContains(Action::CHECK, $response['players'][2]['availableOptions']);
        $this->assertContains(Action::BET, $response['players'][2]['availableOptions']);
    }

    /**
     * @test
     * @return void
     */
    public function actionWillBeOnTheDealerAfterBigBlindActsOnTheFlop()
    {
        $this->gamePlay->start();

        $this->setFlop();

        $this->updateActionsOnNewStreet();

        $request  = $this->setPlayerTwoChecksPost(streetNumber: 1);
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][1]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][1]['availableOptions']);
        $this->assertContains(Action::CHECK, $response['players'][1]['availableOptions']);
        $this->assertContains(Action::BET, $response['players'][1]['availableOptions']);
    }

    private function givenActionsMeanNewStreetIsDealt()
    {
        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        return $this->setPlayerTwoChecksPost();
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
