<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class ThreeHandedDecksTest extends BaseTest
{
    use HasHandFlow;
    use HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setGame()
            ->setHandFlow();
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
