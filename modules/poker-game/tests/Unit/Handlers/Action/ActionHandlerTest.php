<?php

namespace Atsmacode\PokerGame\Tests\Unit\Handlers\Action;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Handlers\Action\ActionHandler;
use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class ActionHandlerTest extends BaseTest
{
    use HasHandFlow;

    private Pot $pots;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setHandFlow();

        $this->pots = $this->container->build(Pot::class);
        $this->actionHandler = $this->container->build(
            ActionHandler::class,
            ['gameState' => $this->gameState]
        );
    }

    /** @test */
    public function handleReturnsInstanceOfGameState()
    {
        $this->handFlow->process($this->gameState);

        $response = $this->actionHandler->handle(
            $this->gameState->getPlayer($this->playerOne->getId())['player_action_id'],
            50,
            Action::CALL_ID,
            1000
        );

        $this->assertInstanceOf(GameState::class, $response);
    }
}
