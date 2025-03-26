<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController\Decks\ThreeHanded;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;
use Atsmacode\PokerGame\Tests\HasStreets;

class PlayerActionControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts, HasStreets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function communityCardsWillNoLongerBeInTheDeck()
    {
        $this->gamePlay->start();

        $this->setFlop();

        $this->setTurn();

        $this->setRiver();

        $request  = $this->executeActionsToContinue();
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
