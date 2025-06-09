<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;
use Atsmacode\PokerGame\Tests\HasStreets;

class ThreeHandedDecksTest extends BaseTest
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
    public function communityCardsWillNoLongerBeInTheDeck()
    {
        $this->handFlow->process($this->gameState);

        $this->setFlop();

        $this->setTurn();

        $this->setRiver();

        $request = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        foreach ($response['communityCards'] as $communityCard) {
            $this->assertNotContains(
                $communityCard['id'],
                array_column($this->gameState->getGameDealer()->getDeck(), 'id')
            );
        }
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
