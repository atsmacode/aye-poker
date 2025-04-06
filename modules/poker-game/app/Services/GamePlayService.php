<?php

namespace Atsmacode\PokerGame\Services;

use Atsmacode\PokerGame\Handlers\ActionHandler\ActionHandler;
use Atsmacode\PokerGame\GamePlay\Game\PotLimitHoldEm;
use Atsmacode\PokerGame\GamePlay\GamePlay;
use Atsmacode\PokerGame\Models\Hand;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Key service for use in Controllers or applications using this package
 * internally. Handles an Action Request and initiates GamePlay response.
 */
class GamePlayService
{
    protected string $game = PotLimitHoldEm::class;

    public function __construct(
        private ContainerInterface $container,
        private ActionHandler $actionHandler,
        private Hand $hands,
    ) {
    }

    public function action(Request $request): array
    {
        $requestBody = $request->toArray();
        $hand = $this->hands->latest();
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
