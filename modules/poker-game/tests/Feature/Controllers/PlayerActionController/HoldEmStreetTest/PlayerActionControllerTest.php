<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController\HoldEmStreetTest;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;
use Atsmacode\PokerGame\Tests\HasStreets;

class PlayerActionControllerTest extends BaseTest
{
    use HasGamePlay;
    use HasActionPosts;
    use HasStreets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setGamePlay();
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDeal3CardsToAFlop()
    {
        $this->gamePlay->start();

        $request = $this->executeActionsToContinue();

        $this->actionControllerResponse($request);

        $this->assertCount(2, $this->handStreetModel->find(['hand_id' => $this->gameState->handId()])->getContent());
        $this->assertCount(3, $this->handStreetModel->getStreetCards($this->gameState->handId(), 2));
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDeal1CardToATurn()
    {
        $this->gamePlay->start();

        $this->setFlop();

        $request = $this->executeActionsToContinue();

        $this->actionControllerResponse($request);

        $this->assertCount(3, $this->handStreetModel->find(['hand_id' => $this->gameState->handId()])->getContent());
        $this->assertCount(1, $this->handStreetModel->getStreetCards($this->gameState->handId(), 3));
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDeal1CardToARiver()
    {
        $this->gamePlay->start();

        $this->setFlop();

        $this->setTurn();

        $request = $this->executeActionsToContinue();

        $this->actionControllerResponse($request);

        $this->assertCount(4, $this->handStreetModel->find(['hand_id' => $this->gameState->handId()])->getContent());
        $this->assertCount(1, $this->handStreetModel->getStreetCards($this->gameState->handId(), 4));
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanReachShowdownWhenAllActivePlayersCanContinueOnTheRiver()
    {
        $this->gamePlay->start();

        $this->setFlop();

        $this->setTurn();

        $this->setRiver();

        $request = $this->executeActionsToContinue();

        $response = $this->actionControllerResponse($request);

        $this->assertNotNull($response['winner']);
    }

    protected function executeActionsToContinue()
    {
        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        $this->givenPlayerTwoFolds();
        $this->givenPlayerTwoCanNotContinue();

        return $this->setPlayerThreeChecksPost();
    }
}
