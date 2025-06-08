<?php

namespace Atsmacode\PokerGame\Tests\Unit\Handlers\Action;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Handlers\Action\ActionHandler;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class ActionHandlerTest extends BaseTest
{
    use HasGamePlay;

    private Pot $pots;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setGamePlay();

        $this->pots = $this->container->build(Pot::class);
        $this->actionHandler = $this->container->build(
            ActionHandler::class,
            ['gameState' => $this->gameState]
        );
    }

    /** @test */
    public function handleReturnsInstanceOfGameState()
    {
        $this->gamePlay->start();
        $this->gameState->updateHandStreets();

        $handStreet = $this->gameState->getHandStreets()[0];

        $response = $this->actionHandler->handle(
            $this->testHand,
            $this->gameState->getPlayers()[0]['player_action_id'],
            $handStreet['id'],
            50,
            Action::CALL_ID,
            1000
        );

        $this->assertInstanceOf(GameState::class, $response);
    }
}
