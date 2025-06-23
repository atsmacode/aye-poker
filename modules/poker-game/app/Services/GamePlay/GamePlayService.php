<?php

namespace Atsmacode\PokerGame\Services\GamePlay;

use Atsmacode\PokerGame\GamePlay\GamePlayResponse;
use Atsmacode\PokerGame\GamePlay\GameStyle\PotLimitHoldEm;
use Atsmacode\PokerGame\GamePlay\HandFlow\HandFlow;
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
        $gameState = $this->sitHandler->handle($requestBody['tableId'], $playerId);

        $handFlow = $this->container->build(HandFlow::class, [/* @phpstan-ignore method.notFound */
            'game' => $this->container->get($this->game),
            'gameState' => $gameState,
        ]);

        return GamePlayResponse::get($handFlow->process($gameState));
    }

    public function action(Request $request): array
    {
        $requestBody = $request->toArray();
        $gameState = $this->actionHandler->handle(
            $requestBody['player_action_id'],
            $requestBody['bet_amount'],
            $requestBody['action_id'],
            $requestBody['stack'] // TODO: possibly don't need the stack in request, could be loaded
        );

        $handFlow = $this->container->build(HandFlow::class, [/* @phpstan-ignore method.notFound */
            'game' => $this->container->get($this->game),
            'gameState' => $gameState,
        ]);

        return GamePlayResponse::get($handFlow->process($gameState));
    }
}
