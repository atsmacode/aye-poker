<?php

namespace Atsmacode\PokerGame\Services\Sit;

use Atsmacode\PokerGame\GamePlay\GamePlay;
use Atsmacode\PokerGame\GamePlay\GameStyle\PotLimitHoldEm;
use Atsmacode\PokerGame\Handlers\Sit\SitHandler;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Key service for use in Controllers or applications using this package
 * internally. Handles a user visiting the page/table and initiates GamePlay response.
 */
class SitService
{
    private string $game = PotLimitHoldEm::class;

    public function __construct(
        private ContainerInterface $container,
        private SitHandler $sitHandler
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

        return $gameState->handIsActive()
            ? $gamePlay->play($gameState)
            : $gamePlay->start();
    }
}
