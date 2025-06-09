<?php

namespace Atsmacode\PokerGame\Services\GamePlay;

use Atsmacode\PokerGame\GamePlay\GamePlay;
use Atsmacode\PokerGame\GamePlay\GamePlayResponse;
use Atsmacode\PokerGame\GamePlay\GameStyle\PotLimitHoldEm;
use Atsmacode\PokerGame\Handlers\Action\ActionHandler;
use Atsmacode\PokerGame\Handlers\Sit\SitHandler;
use Atsmacode\PokerGame\Repository\Hand\HandRepository;
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
        private SitHandler $sitHandler,
        private HandRepository $handRepo,
    ) {
    }

    public function sit(Request $request, ?int $playerId = null): array
    {
        $requestBody = $request->toArray();
        $gameId = $requestBody['gameId'] ?? null;
        $tableId = $requestBody['tableId'];

        $gameState = $this->sitHandler->handle($tableId, $playerId, $gameId);

        $gamePlay = $this->container->build(GamePlay::class, [/* @phpstan-ignore method.notFound */
            'game' => $this->container->get($this->game),
            'gameState' => $gameState,
        ]);

        return GamePlayResponse::get($gamePlay->play($gameState));
    }

    public function action(Request $request): array
    {
        $requestBody = $request->toArray();
        $hand = $this->handRepo->getLatest(); // TODO: hand retrieved should be associated with the game/table, not latest of any

        $gameState = $this->actionHandler->handle(
            $hand,
            $requestBody['player_action_id'],
            $requestBody['bet_amount'],
            $requestBody['action_id'],
            $requestBody['stack']
        );

        $gamePlay = $this->container->build(GamePlay::class, [/* @phpstan-ignore method.notFound */
            'game' => $this->container->get($this->game),
            'gameState' => $gameState,
        ]);

        return GamePlayResponse::get($gamePlay->play($gameState));
    }
}
