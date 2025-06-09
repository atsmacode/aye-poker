<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;
use Atsmacode\PokerGame\Tests\HasStreets;

class SixHandedStreetTest extends BaseTest
{
    use HasHandFlow;
    use HasActionPosts;
    use HasStreets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isSixHanded()
            ->setHand();
    }

    /**
     * @test
     *
     * @return void
     */
    public function whenDealerIsSeatSixAndOnlySmallBlindCallsAndBigBlindChecksItCanDealFlop()
    {
        $this->givenCurrentDealerIs($this->playerSix->getId())
            ->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->givenPlayerThreeFolds();
        $this->givenPlayerThreeCanNotContinue();

        $this->givenPlayerFourFolds();
        $this->givenPlayerFourCanNotContinue();

        $this->givenPlayerFiveFolds();
        $this->givenPlayerFiveCanNotContinue();

        $this->givenPlayerSixFolds();
        $this->givenPlayerSixCanNotContinue();

        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        $request = $this->setPlayerTwoChecksPost();

        $this->actionControllerResponse($request);

        $this->assertCount(2, $this->handStreets->find(['hand_id' => $this->gameState->handId()])->getContent());
        $this->assertCount(3, $this->handStreetCardRepo->getStreetCards($this->gameState->handId(), 2));
    }
}
