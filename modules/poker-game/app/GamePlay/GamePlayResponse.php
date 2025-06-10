<?php

namespace Atsmacode\PokerGame\GamePlay;

use Atsmacode\PokerGame\State\Game\GameState;

class GamePlayResponse
{
    public static function get(GameState $gameState): array
    {
        return [
            'pot' => $gameState->getPot(),
            'communityCards' => $gameState->getCommunityCards(),
            'players' => $gameState->getPlayerState(),
            'winner' => $gameState->getWinner(),
            'sittingOut' => $gameState->getSittingOutPlayers(),
            'mode' => $gameState->getGameMode(),
        ];
    }
}
