<?php

namespace Atsmacode\PokerGame\GamePlay;

use Atsmacode\PokerGame\State\Game\GameState;

class GamePlayResponse
{
    public static function get(GameState $gameState): array
    {
        $lastAction = $gameState->getLatestAction();
        $streets = $gameState->getHandStreets();
        $latestStreet = array_pop($streets);

        return [
            'pot' => $gameState->getPot(),
            'communityCards' => $gameState->getCommunityCards(),
            'players' => $gameState->getPlayerState(),
            'winner' => $gameState->getWinner(),
            'sittingOut' => $gameState->getSittingOutPlayers(),
            'mode' => $gameState->getGameMode(),
            'message' => $gameState->getMessage(),
            'lastAction' => $lastAction->getActionId(),
            'toCall' => $lastAction->getBetAmount(),
            'street' => $latestStreet['name'] ?? 'Pre-flop'
        ];
    }
}
