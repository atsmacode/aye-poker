<?php

namespace Atsmacode\PokerGame\Services;

use Atsmacode\PokerGame\ActionHandler\ActionHandler;
use Atsmacode\PokerGame\Game\PotLimitHoldEm;
use Atsmacode\PokerGame\GamePlay\GamePlay;
use Atsmacode\PokerGame\Models\Hand;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class GamePlayService
{
    /**
     * To be set to the fully qualified class name of an
     * implementation of the Game interface.
     */
    protected string $game = PotLimitHoldEm::class;

    public function __construct(
        private ContainerInterface $container,
        private ActionHandler $actionHandler,
        private Hand $hand,
    ) {
    }

    public function action(Request $request): array
    {
        $requestBody = $request->toArray();
        $hand = $this->hand->latest();
        $gameState = $this->actionHandler->handle(
            $hand,
            $requestBody['player_id'],
            $requestBody['table_seat_id'],
            $requestBody['hand_street_id'],
            $requestBody['bet_amount'],
            $requestBody['action_id'],
            $requestBody['active'],
            $requestBody['stack']
        );

        $gamePlay = $this->container->build(GamePlay::class, [/* @phpstan-ignore method.notFound */
            'game' => $this->container->get($this->game),
            'gameState' => $gameState,
        ]);

        return $gamePlay->play($gameState);
    }
}
