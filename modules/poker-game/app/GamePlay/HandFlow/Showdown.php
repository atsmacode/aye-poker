<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\GamePlay\Showdown\Showdown as TheShowdown;
use Atsmacode\PokerGame\Services\Pots\PotService;
use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Responsible for the actions required if the hand has reached a showdown.
 */
class Showdown implements ProcessesGameState
{
    public function __construct(private PotService $potService)
    {
    }

    public function process(GameState $gameState): GameState
    {
        $gameState
            ->loadPlayers()
            ->loadWholeCards(); // This is done again in PlayerState, try reduce to 1 call

        $winner = (new TheShowdown($gameState))
            ->compileHands()
            ->decideWinner();

        $gameState->setWinner($winner);

        $this->potService->awardPot(
            $winner['player']['stack'],
            $gameState->getPot(),
            $winner['player']['player_id'],
            $winner['player']['table_id']
        );

        $gameState->getHand()->complete();

        return $gameState;
    }
}
