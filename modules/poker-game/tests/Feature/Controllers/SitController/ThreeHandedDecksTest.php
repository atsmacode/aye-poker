<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class ThreeHandedDecksTest extends BaseTest
{
    use HasGamePlay;
    use HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setGame()
            ->setGamePlay();
    }

    /**
     * @test
     *
     * @return void
     */
    public function dealtWholeCardsWillNolongerBeInTheDeck()
    {
        $response = $this->sitControllerResponse();

        foreach ($response['players'] as $player) {
            foreach ($player['whole_cards'] as $wholeCard) {
                $this->assertNotContains(
                    $wholeCard['id'],
                    array_column($this->gameState->getGameDealer()->getDeck(), 'id')
                );
            }
        }
    }
}
