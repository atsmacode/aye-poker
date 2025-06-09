<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;
use Atsmacode\PokerGame\Tests\HasStreets;

class StreetTest extends BaseTest
{
    use HasHandFlow;
    use HasActionPosts;
    use HasStreets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setHandFlow();
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDeal3CardsToAFlop()
    {
        $this->handFlow->process($this->gameState);

        $request = $this->executeActionsToContinue();

        $this->actionControllerResponse($request);

        $this->assertCount(2, $this->handStreets->find(['hand_id' => $this->gameState->handId()])->getContent());
        $this->assertCount(3, $this->handStreetCardRepo->getStreetCards($this->gameState->handId(), 2));
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDeal1CardToATurn()
    {
        $this->handFlow->process($this->gameState);

        $this->setFlop();

        $request = $this->executeActionsToContinue();

        $this->actionControllerResponse($request);

        $this->assertCount(3, $this->handStreets->find(['hand_id' => $this->gameState->handId()])->getContent());
        $this->assertCount(1, $this->handStreetCardRepo->getStreetCards($this->gameState->handId(), 3));
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDeal1CardToARiver()
    {
        $this->handFlow->process($this->gameState);

        $this->setFlop();

        $this->setTurn();

        $request = $this->executeActionsToContinue();

        $this->actionControllerResponse($request);

        $this->assertCount(4, $this->handStreets->find(['hand_id' => $this->gameState->handId()])->getContent());
        $this->assertCount(1, $this->handStreetCardRepo->getStreetCards($this->gameState->handId(), 4));
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanReachShowdownWhenAllActivePlayersCanContinueOnTheRiver()
    {
        $this->handFlow->process($this->gameState);

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
