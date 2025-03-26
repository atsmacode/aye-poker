<?php

namespace Atsmacode\PokerGame\Controllers\Dev;

use Atsmacode\PokerGame\GamePlay\GamePlay;
use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\Hand;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\HttpFoundation\Response;

abstract class SitController
{
    /**
     * To be set to the fully qualified class name of an 
     * implementation of the Game interface.
     */
    protected string $game = '';

    private Hand $handModel;

    public function __construct(private ServiceManager $container)
    {
        $this->handModel = $container->build(Hand::class);
    }

    public function play($tableId = null, $currentDealer = null): Response
    {
        $hand = $this->handModel->create(['table_id' => $tableId ?? 1]);

        $gamePlayService = $this->container->build(GamePlay::class, [
            'game'      => $this->container->get($this->game),
            'gameState' => $this->container->build(GameState::class, ['hand' => $hand])
        ]);
        $gamePlay = $gamePlayService->start($currentDealer ?? null);

        return new Response(json_encode([
            'pot'            => $gamePlay['pot'],
            'communityCards' => $gamePlay['communityCards'],
            'players'        => $gamePlay['players'],
            'winner'         => $gamePlay['winner']
        ]));
    }
}
