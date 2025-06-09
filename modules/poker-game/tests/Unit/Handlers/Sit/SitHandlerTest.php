<?php

namespace Atsmacode\PokerGame\Tests\Unit\Handlers\Sit;

use Atsmacode\PokerGame\Handlers\Sit\SitHandler;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class SitHandlerTest extends BaseTest
{
    use HasHandFlow;

    private SitHandler $sitHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setHandFlow();

        $this->sitHandler = $this->container->build(
            SitHandler::class,
            ['gameState' => $this->gameState]
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function handleReturnsInstanceOfGameState()
    {
        $response = $this->sitHandler->handle(
            $this->testTable->getId(),
            $this->playerOne->getId(),
        );

        $this->assertInstanceOf(GameState::class, $response);
    }
}
