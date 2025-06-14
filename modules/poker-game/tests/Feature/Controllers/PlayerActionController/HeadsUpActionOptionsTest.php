<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;
use Atsmacode\PokerGame\Tests\HasStreets;

class HeadsUpActionOptionsTest extends BaseTest
{
    use HasHandFlow;
    use HasActionPosts;
    use HasStreets;

    private PlayerAction $playerAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->playerAction = $this->container->build(PlayerAction::class);

        $this->isHeadsUp()
            ->setHand()
            ->setHandFlow();
    }

    /**
     * @test
     *
     * @return void
     */
    public function thePlayerFirstToActCanFoldCheckOrBet()
    {
        $this->handFlow->process($this->gameState);

        $request = $this->givenActionsMeanNewStreetIsDealt();
        $response = $this->actionControllerResponse($request);

        $this->assertTrue($response['players'][2]['action_on']);

        $this->assertContains(Action::FOLD, $response['players'][2]['availableOptions']);
        $this->assertContains(Action::CHECK, $response['players'][2]['availableOptions']);
        $this->assertContains(Action::BET, $response['players'][2]['availableOptions']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function actionWillBeOnTheDealerAfterBigBlindActsOnTheFlop()
    {
        $this->handFlow->process($this->gameState);

        $this->setFlop();

        $this->updateActionsOnNewStreet();

        $request = $this->setPlayerTwoChecksPost();
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
        $this->gameState->loadHandStreets();

        $handStreets = $this->gameState->getHandStreets();
        $latestStreet = array_pop($handStreets);

        $this->playerAction->find(['hand_id' => $this->gameState->handId()])
            ->updateBatch([
                'action_id' => null,
                'hand_street_id' => $latestStreet['id'],
            ], 'hand_id = '.$this->gameState->handId());
    }
}
