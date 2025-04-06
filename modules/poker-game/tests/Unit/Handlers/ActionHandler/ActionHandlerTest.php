<?php

namespace Atsmacode\PokerGame\Tests\Unit\Handlers\ActionHandler;

use Atsmacode\PokerGame\Handlers\ActionHandler\ActionHandler;
use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\Models\Pot;
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
            ['gameState' => $this->gameState,
            ]);
    }

    /** @test */
    public function handleReturnsInstanceOfGameState()
    {
        $this->gamePlay->start();
        $this->gameState->updateHandStreets();

        $handStreet = $this->gameState->getHandStreets()[0];

        $response = $this->actionHandler->handle(
            $this->testHand,
            $this->playerOne->getId(),
            $this->tableSeatOne->getId(),
            $handStreet['id'],
            50,
            Action::CALL_ID,
            1,
            1000
        );

        $this->assertInstanceOf(GameState::class, $response);
    }
}
